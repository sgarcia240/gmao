#!/bin/sh
set -e

# Limpiar caché de configuración en cada inicio
php artisan config:clear || true

# Arrancar Apache en primer plano
exec apache2-foreground


