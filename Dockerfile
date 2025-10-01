# Imagen base liviana de PHP con FPM
FROM php:8.2-fpm-alpine

# Instala dependencias del sistema y herramientas de PostgreSQL
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    unzip \
    libpq \
    postgresql-dev \
    # Instala extensiones PHP para Laravel y PostgreSQL
    && docker-php-ext-install pdo pdo_pgsql opcache

# Copia Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala dependencias de Laravel (producción)
RUN composer install --no-dev --optimize-autoloader

# >>> PASO CRÍTICO: Limpiar la caché de configuración en la etapa de construcción
# Esto evita que se usen credenciales antiguas si el archivo de caché está en Git.
RUN php artisan config:clear

# Asigna permisos correctos para Laravel (storage y cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Comando de inicio del contenedor (solo migraciones y arranque)
# La limpieza ya se hizo arriba.
CMD php artisan migrate --force && vendor/bin/heroku-php-apache2 public/
