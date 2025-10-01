FROM php:8.2-fpm-alpine

RUN apk add --no-cache curl git unzip libpq postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql opcache \
    && rm -rf /var/cache/apk/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html
COPY . .

RUN chown -R www-data:www-data .

USER www-data
RUN composer install --no-dev --optimize-autoloader
USER root

RUN chown -R www-data:www-data storage bootstrap/cache \
    && chmod -R 775 storage bootstrap/cache

# Copiar env mínimo y generar APP_KEY
RUN cp .env.example .env || true
RUN php artisan key:generate --ansi

EXPOSE 10000

# Usar servidor PHP interno que Render detecta como HTTP
CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "10000"]

# ----------------------------
# # Imagen base PHP-FPM ligera
# # ----------------------------
# FROM php:8.2-fpm-alpine

# # Instala dependencias del sistema y PostgreSQL
# RUN apk add --no-cache \
#     curl \
#     git \
#     unzip \
#     libpq \
#     postgresql-dev \
#     # Instala extensiones de PHP
#     && docker-php-ext-install pdo pdo_pgsql opcache \
#     # Limpieza de caché
#     && rm -rf /var/cache/apk/*

# # Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Directorio de trabajo
# WORKDIR /var/www/html

# # Copia el proyecto
# COPY . .

# # Dependencias Laravel
# # Usamos --no-dev para producción
# RUN composer install --no-dev --optimize-autoloader

# # Limpieza y optimización de configuración de Laravel
# RUN php artisan config:clear \
#     && php artisan route:clear \
#     && php artisan view:clear \
#     && php artisan cache:clear \
#     && php artisan optimize

# # Permisos
# # Establecemos permisos para que el servidor web pueda escribir en los directorios necesarios
# RUN chown -R www-data:www-data storage bootstrap/cache \
#     && chmod -R 775 storage bootstrap/cache

# # Puerto que Render expone automáticamente.
# # NOTA: Aunque el CMD no usa EXPOSE directamente, es una buena práctica dejarlo.
# EXPOSE 10000

# # Comando de Ejecución (Entrypoint)
# # Usamos el servidor nativo de Laravel para un entorno más robusto en producción
# # Asegúrate de usar $PORT, que es la variable de entorno de Render.
# CMD ["php", "artisan", "serve", "--host", "0.0.0.0", "--port", "10000"]

#---------------------------------------------------------------------

# ----------------------------
# # Imagen base PHP-FPM ligera
# # ----------------------------
# FROM php:8.2-fpm-alpine

# # Instala dependencias del sistema y PostgreSQL
# RUN apk add --no-cache \
#     curl \
#     git \
#     unzip \
#     libpq \
#     postgresql-dev \
#     && docker-php-ext-install pdo pdo_pgsql opcache \
#     && rm -rf /var/cache/apk/*

# # Composer
# COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# # Directorio de trabajo
# WORKDIR /var/www/html

# # Copia el proyecto
# COPY . .

# # Dependencias Laravel
# RUN composer install --no-dev --optimize-autoloader

# # Limpia caché config
# RUN php artisan config:clear

# # Permisos
# RUN chown -R www-data:www-data storage bootstrap/cache \
#     && chmod -R 775 storage bootstrap/cache

# # Puerto que Render expone automáticamente
# EXPOSE 10000

# # Servidor PHP interno que Render detecta como HTTP
# CMD php -S 0.0.0.0:$PORT -t public
