FROM composer:latest AS build

COPY ./src/ /kp_esportes/
WORKDIR /kp_esportes/

RUN apk update
RUN composer update
RUN composer install
RUN composer dump-autoload

FROM php:8.2-apache AS server

COPY --from=build /kp_esportes/ /var/www/html/

RUN vendor/bin/phpunit tests

RUN apt update
RUN apt install -y libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql pgsql
RUN a2enmod rewrite

EXPOSE 80