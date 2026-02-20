<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Validation\ValidationException;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;

use Throwable;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__ . '/../routes/web.php',
        api: __DIR__ . '/../routes/api.php',
        commands: __DIR__ . '/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        //
    })
    ->withExceptions(function (Exceptions $exceptions): void {

        
        $exceptions->render(function (Throwable $e, Request $request) {

            //Solo para rutas API
           if ($request->is('api/*')) {
            
            // Recurso no encontrado (404)
            if ($e instanceof NotFoundHttpException) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Recurso no encontrado'
                ], 404);
            }
            
            // Errores de validación (422)
            if ($e instanceof ValidationException) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Errores de validación',
                    'errors' => $e->errors(),
                ], 422);
            }
            
            // No autenticado (401) - sin token
            if ($e instanceof AuthenticationException) {
                return response()->json([
                    'ok' => false,
                    'message' => 'No autenticado'
                ], 401);
            }
            
            // Token inválido (401) - cuando intenta redirigir a login
            if ($e instanceof RouteNotFoundException && str_contains($e->getMessage(), 'login')) {
                return response()->json([
                    'ok' => false,
                    'message' => 'Token inválido o sesión expirada'
                ], 401);
            }
            
            // Error genérico (500)
            return response()->json([
                'ok' => false,
                'message' => 'Error interno del servidor'
            ], 500);
        }
        
        // Si no es API, dejar que Laravel maneje normalmente
        return null;
        });
    })
    ->create();
