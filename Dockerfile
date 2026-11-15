FROM php:8.3-apache

# 1. Instalar dependencias del sistema, Node.js 20, extensiones PHP para PostgreSQL y GD
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    curl \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) pdo pdo_pgsql pgsql zip gd \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Habilitar mod_rewrite de Apache y reconfigurar el DocumentRoot a /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite

# 3. Configuración PHP para subida de archivos
RUN echo "upload_max_filesize=20M\npost_max_size=25M" > /usr/local/etc/php/conf.d/uploads.ini

# 4. Copiar ejecutable de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copiar código fuente del proyecto
WORKDIR /var/www/html
COPY . .

# 6. Instalar dependencias de PHP (Composer) y compilar frontend (Vite)
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist \
    && npm ci || npm install \
    && npm run build

# 7. Asignar permisos correctos a las carpetas de almacenamiento de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Copiar y sanitizar el script de arranque
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i -e 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

# 9. Punto de entrada único
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]