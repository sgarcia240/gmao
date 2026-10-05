FROM php:8.3-apache

# 1. Instalar dependencias del sistema y extensiones PHP para PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# 2. Habilitar mod_rewrite de Apache para Laravel
RUN a2enmod rewrite

# 3. Cambiar el DocumentRoot de Apache a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf

# 4. Copiar ejecutable de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copiar código del proyecto
WORKDIR /var/www/html
COPY . .

# 6. Instalar dependencias de Laravel sin las dev
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts

# 7. Dar permisos a las carpetas de almacenamiento
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Dar permisos de ejecución al script de arranque
RUN chmod +x docker/entrypoint.sh

ENTRYPOINT ["docker/entrypoint.sh"]