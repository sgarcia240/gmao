#!/bin/sh
set -e

# Limpiar cache de configuracion, rutas y vistas al arrancar el contenedor
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Ejecutar migraciones pendientes en Neon
php artisan migrate --force

# Arrancar el servidor Apache
exec apache2-foreground


