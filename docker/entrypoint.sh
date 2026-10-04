#!/bin/sh

# Adaptar el puerto dinámico asignado por Render ($PORT)
PORT="${PORT:-80}"
sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

# Limpiar y regenerar cachés de Laravel
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Ejecutar migraciones automáticamente en PostgreSQL
php artisan migrate --force

# Iniciar servidor Apache
exec apache2-foreground