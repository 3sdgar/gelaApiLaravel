# Imagen base liviana de PHP con FPM
FROM php:8.2-fpm-alpine

# Instala dependencias necesarias para Laravel y PostgreSQL
RUN apk add --no-cache \
    curl \
    git \
    unzip \
    libpq \
    postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql opcache

# Copia Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto al contenedor
COPY . .

# Instala dependencias de Laravel (modo producción)
RUN composer install --no-dev --optimize-autoloader

# Configura permisos correctos (storage y bootstrap/cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Variables de entorno básicas (Render las sobreescribe con tus valores en el Dashboard)
ENV APP_ENV=production
ENV APP_DEBUG=false

# Render expone el puerto 10000 por defecto
EXPOSE 10000

# Comando de inicio:
# 1. Ejecuta migraciones forzadas
# 2. Cachea configuración
# 3. Inicia PHP-FPM en el puerto que Render requiere
CMD php artisan migrate --force && php artisan config:cache && php-fpm --nodaemonize --fpm-config /usr/local/etc/php-fpm.conf
