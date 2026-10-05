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
# Configurar el DocumentRoot de Apache a la carpeta /public de Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public

RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf \
    && sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/conf-available/*.conf \
    && a2enmod rewrite
    # Instalar librerías del sistema y extensiones de PHP para PostgreSQL
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql pgsql
    # Instalar Node.js para compilar assets de Vite
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs
    # Copiar el script entrypoint a la carpeta de ejecutables del sistema
COPY entrypoint.sh /usr/local/bin/entrypoint.sh

# Convertir saltos de línea CRLF (Windows) a LF (Linux) y dar permisos de ejecución
RUN sed -i -e 's/\r$//' /usr/local/bin/entrypoint.sh \
    && chmod +x /usr/local/bin/entrypoint.sh

# Establecer el punto de entrada del contenedor
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]

# Copiar archivos del proyecto e instalar dependencias de NPM
COPY . /var/www/html
WORKDIR /var/www/html
RUN npm ci || npm install
RUN npm run build

ENTRYPOINT ["docker/entrypoint.sh"]