FROM php:8.1-fpm

USER root

RUN apt-get update && apt-get install -y libpq-dev libpng-dev \
    && docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql \
    && docker-php-ext-install pdo pdo_pgsql pgsql

RUN docker-php-ext-install bcmath gd

RUN mkdir -p /var/www/app && \
    chown -R www-data:www-data /var/www/app

# latest composer is 2.3.5
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app
