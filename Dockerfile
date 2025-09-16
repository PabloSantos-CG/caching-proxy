FROM php:8.1.33-apache

WORKDIR /var/www/html

RUN a2enmod rewrite
RUN pecl install redis-6.2.0 && docker-php-ext-enable redis

COPY --from=composer/composer:2.8-bin /composer /usr/bin/composer
COPY . .

RUN composer install --no-interaction --optimize-autoloader
