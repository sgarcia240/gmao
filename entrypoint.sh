#!/bin/sh
set -e
# Si vendor no existe por cualquier motivo, instalarlo antes de ejecutar artisan
if [ ! -f /var/www/html/vendor/autoload.php ]; then
    composer install --no-dev --optimize-autoloader --no-interaction
fi

# Limpiar cache de configuracion, rutas y vistas al arrancar el contenedor
php artisan config:clear
php artisan cache:clear
php artisan view:clear

# Ejecutar migraciones pendientes en Neon
php artisan migrate --force
# Crear enlace simbolico de storage para servir fotos desde /storage
php artisan storage:link --force

# Arrancar el servidor Apache
exec apache2-foreground


