FROM php:8.3-apache

# 1. Instalar dependencias del sistema, Node.js 20 y extensiones PHP para PostgreSQL
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    curl \
    && curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs \
    && docker-php-ext-install pdo pdo_pgsql pgsql zip \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 2. Habilitar mod_rewrite de Apache y reconfigurar el DocumentRoot a /public
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite

# 3. Copiar ejecutable de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 4. Copiar código fuente del proyecto
WORKDIR /var/www/html
COPY . .

# 5. Instalar dependencias de PHP (Composer) y compilar frontend (Vite)
RUN composer install --no-dev --optimize-autoloader --no-interaction --no-scripts \
    && npm ci || npm install \
    && npm run build

# 6. Asignar permisos correctos a las carpetas de almacenamiento de Laravel
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# 7. Copiar y sanitizar el script de arranque (entrypoint.sh desde la raíz)
COPY entrypoint.sh /usr/local/bin/entrypoint.sh
RUN sed -i -e 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

# 8. Punto de entrada único
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]