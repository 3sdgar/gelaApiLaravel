FROM php:8.2-fpm-alpine

# Dependencias de sistema y PostgreSQL
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

WORKDIR /var/www/html

# Proyecto
COPY . .

# Dependencias Laravel
RUN composer install --no-dev --optimize-autoloader

# Limpia cache config
RUN php artisan config:clear

# Permisos
RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Puerto que Render expone
EXPOSE 10000

# Solo PHP-FPM
CMD php-fpm --nodaemonize --fpm-config /usr/local/etc/php-fpm.conf
