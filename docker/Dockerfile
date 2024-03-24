FROM php:8.2-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

COPY . /var/www

RUN chown -R www-data:www-data /var/www
