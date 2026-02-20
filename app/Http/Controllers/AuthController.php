<?php

namespace App\Http\Controllers;

use App\Http\Requests\LoginRequest;
use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Requests\RegistroRequest;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    
    public function register(RegistroRequest $request){
        //Validar el registro

        $data = $request->validated();

        //Crear el usuario
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password'])
        ]);

        //Retornamos una respuesta
        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => $user
        ];
    }
    
    public function login(LoginRequest $request){
        //Validar login

        $data = $request->validated();

        //Revisar Password

        if(!Auth::attempt($data)){
            return response([
                'ok' => false,
                'errors' => ['El Email o el password son incorrectos']
            ], 422);
        }

        //Autenticar al usuario

        $user = Auth::user();

        return [
            'token' => $user->createToken('token')->plainTextToken,
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
                'admin' => $user->admin
            ]
        ];
    }

    public function logout(Request $request) {
        $user = $request->user();
        $user->currentAccessToken()->delete();

        return [
            'ok' => true,
            'message' => "Sesión cerrada correctamente"
        ];
    }
}
