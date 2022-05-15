FROM php:8.1-fpm

USER root

RUN docker-php-ext-install bcmath
RUN mkdir -p /var/www/app && \
    chown -R www-data:www-data /var/www/app

# latest composer is 2.3.5
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/app
