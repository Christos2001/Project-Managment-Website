FROM php:8.2-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install mysqli pdo pdo_mysql zip


COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

COPY php.ini /usr/local/etc/php/php.ini

COPY composer.json composer.lock* ./

RUN composer install --no-dev --optimize-autoloader --no-interaction


COPY . .


RUN chown -R www-data:www-data /var/www/html

EXPOSE 80