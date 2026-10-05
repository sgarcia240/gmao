#!/bin/sh

# Adaptar el puerto dinámico asignado por Render ($PORT)
PORT="${PORT:-80}"
sed -i "s/80/${PORT}/g" /etc/apache2/ports.conf /etc/apache2/sites-available/*.conf

# Limpiar cachés antiguas de configuración
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Ejecutar migraciones automáticamente en PostgreSQL
php artisan migrate --force

# Iniciar servidor Apache
exec apache2-foreground