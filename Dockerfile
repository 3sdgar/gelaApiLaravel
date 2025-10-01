FROM php:8.2-fpm-alpine

RUN apk add --no-cache \
    nginx \
    curl \
    git \
    unzip \
    libpq \
    postgresql-dev \
    && docker-php-ext-install pdo pdo_pgsql opcache \
    && rm -rf /var/cache/apk/*

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY . .

RUN composer install --no-dev --optimize-autoloader

RUN php artisan config:clear

RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Configuración de Nginx en una sola línea para evitar errores de análisis.
RUN echo 'server {\n    listen 8080;\n    root /var/www/html/public;\n    index index.php index.html;\n\n    location / {\n        try_files \$uri \$uri/ /index.php?\$query_string;\n    }\n\n    location ~ \\.php\$ {\n        fastcgi_split_path_info ^(.+\\.php)(/.+)\$;\n        fastcgi_pass 127.0.0.1:9000;\n        fastcgi_index index.php;\n        include fastcgi_params;\n        fastcgi_param SCRIPT_FILENAME \$document_root\$fastcgi_script_name;\n        fastcgi_param PATH_INFO \$fastcgi_path_info;\n    }\n}' > /etc/nginx/conf.d/default.conf

EXPOSE 8080

CMD php artisan migrate --force && \
    /usr/sbin/php-fpm82 -F & \
    nginx -g "daemon off;"
