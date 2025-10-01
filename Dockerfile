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

# Copia Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala dependencias de Laravel (producción)
RUN composer install --no-dev --optimize-autoloader

# Limpia caché de configuración
RUN php artisan config:clear

# Permisos correctos para storage y cache
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Puerto que Render expone
EXPOSE 10000

# Arranca PHP-FPM
CMD php-fpm --nodaemonize --fpm-config /usr/local/etc/php-fpm.conf
