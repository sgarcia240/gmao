#!/bin/bash

# 1. Eliminar físicamente archivos de caché persistentes
rm -f /var/www/html/bootstrap/cache/*.php

# 2. Limpiar cachés internas de Laravel
php artisan config:clear
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# 3. Asignar permisos al usuario de Apache (www-data) en tiempo de ejecución
chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache
chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 4. Iniciar Apache
exec apache2-foreground


