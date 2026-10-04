# 1. Instalar librerías del sistema operativo requeridas por PHP y Composer
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpq-dev \
    libzip-dev \
    && docker-php-ext-install pdo pdo_pgsql zip

# 2. Instalar Composer ejecutable
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 3. Copiar archivos del proyecto
WORKDIR /var/www/html
COPY . .

# 4. Ejecutar composer install
RUN composer install --no-dev --optimize-autoloader --no-interaction