FROM composer:latest AS build

COPY ./src/ /kp_esportes/
WORKDIR /kp_esportes/

RUN apk update
RUN composer update
RUN composer install
RUN composer dump-autoload

RUN vendor/bin/phpunit tests

FROM php:8.2-apache AS server

RUN apt update
RUN apt install -y libpq-dev
RUN docker-php-ext-install pdo pdo_mysql pgsql
RUN a2enmod rewrite

COPY --from=build /kp_esportes/ /var/www/html/

EXPOSE 80