FROM node:22-bookworm-slim AS frontend

WORKDIR /app
COPY package.json package-lock.json ./
RUN npm ci

COPY . .
RUN npm run build

FROM php:8.4-apache

WORKDIR /var/www/html

RUN docker-php-ext-install pdo_mysql \
    && a2enmod rewrite \
    && sed -i 's/^Listen 80$/Listen 10000/' /etc/apache2/ports.conf \
    && sed -i \
        -e 's/<VirtualHost \*:80>/<VirtualHost *:10000>/' \
        -e 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|' \
        /etc/apache2/sites-available/000-default.conf \
    && printf '<Directory /var/www/html/public>\n    AllowOverride All\n    Require all granted\n</Directory>\n' \
        > /etc/apache2/conf-available/laravel.conf \
    && a2enconf laravel

COPY --from=composer:2 /usr/bin/composer /usr/local/bin/composer
COPY . .
COPY --from=frontend /app/public/build ./public/build

RUN composer install --no-dev --no-interaction --prefer-dist --optimize-autoloader \
    && mkdir -p storage/framework/cache/data storage/framework/sessions storage/framework/views \
    && chown -R www-data:www-data storage bootstrap/cache public/images

EXPOSE 10000
CMD ["apache2-foreground"]
