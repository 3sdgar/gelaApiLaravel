# ----------------------------
# Imagen base PHP-FPM ligera
# ----------------------------
FROM php:8.2-fpm-alpine

# Instala dependencias del sistema y PostgreSQL
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    libpq \
    postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql opcache \
    && rm -rf /var/cache/apk/*

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Copia el proyecto
COPY . .

# Dependencias Laravel
RUN composer install --no-dev --optimize-autoloader

# Limpia caché config
RUN php artisan config:clear

# Permisos
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Puerto que Render expone automáticamente
EXPOSE 10000

# Servidor PHP interno que Render detecta como HTTP
CMD php -S 0.0.0.0:$PORT -t public
