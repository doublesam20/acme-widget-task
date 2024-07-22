FROM php:8.2-cli

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
COPY . /app

WORKDIR /app

RUN composer install
