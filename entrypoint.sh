#!/bin/sh
set -e

# Limpiar cache de configuracion y rutas al arrancar el contenedor
php artisan config:clear
php artisan cache:clear

# Arrancar el servidor Apache
exec apache2-foreground


