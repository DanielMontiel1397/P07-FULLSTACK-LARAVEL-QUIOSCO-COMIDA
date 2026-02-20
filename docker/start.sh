#!/bin/sh

# Generar key si no existe
php artisan key:generate --force

# Limpiar y cachear configuración
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Correr migraciones
php artisan migrate --force

# Iniciar PHP-FPM en background
php-fpm &

# Iniciar Nginx en foreground
nginx -g "daemon off;"
```

**1.4 — Crea el archivo `.dockerignore`**

En la raíz de Laravel, crea `.dockerignore`:
```
.git
.env
node_modules
vendor
.DS_Store
*.log