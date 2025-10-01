# Imagen base liviana de PHP con FPM
FROM php:8.2-fpm-alpine

# Instala dependencias del sistema, Nginx, git, y PostgreSQL
RUN apk add --no-cache \
    nginx \
    curl \
    git \
    unzip \
    libpq \
    postgresql-dev \
    # Instala extensiones PHP necesarias
    && docker-php-ext-install pdo pdo_pgsql opcache \
    # Limpia el cache de APK
    && rm -rf /var/cache/apk/*

# Copia Composer desde la imagen oficial
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Establece el directorio de trabajo
WORKDIR /var/www/html

# Copia los archivos del proyecto
COPY . .

# Instala dependencias de Laravel (producción)
RUN composer install --no-dev --optimize-autoloader

# >>> Limpiar la caché de configuración en la etapa de construcción
# Esto es CRÍTICO para que tome las variables de Render
RUN php artisan config:clear

# Asigna permisos correctos para Laravel (storage y cache)
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# ----------------------------------------------------
# CONFIGURACIÓN DEL SERVIDOR WEB (Nginx)
# ----------------------------------------------------

# Este es el cambio clave: usamos 'sh -c' y un 'cat' multi-línea para evitar problemas de escape.
RUN sh -c "cat > /etc/nginx/conf.d/default.conf <<-EOF
server {
    listen 8080;
    root /var/www/html/public;
    index index.php index.html;

    location / {
        try_files \$uri \$uri/ /index.php?\$query_string;
    }

    location ~ \.php\$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)\$;
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;
        fastcgi_param PATH_INFO \$fastcgi_path_info;
    }
}
EOF"

# El puerto 8080 es el que usa Render por defecto
EXPOSE 8080

# ----------------------------------------------------
# COMANDO DE INICIO
# ----------------------------------------------------

# Inicia la migración (si no se ha hecho), luego inicia Nginx en primer plano y FPM.
# Los '&&' aseguran que Nginx y FPM solo se ejecutan si la migración es exitosa.
CMD php artisan migrate --force && nginx -g "daemon off;" & php-fpm -F
