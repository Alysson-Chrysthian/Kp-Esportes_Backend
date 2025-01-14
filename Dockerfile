FROM composer:latest AS build

COPY ./src/ /kp_esportes/
WORKDIR /kp_esportes/

RUN apt-get install php-xml
RUN composer update
RUN composer install
RUN composer dump-autoload

FROM php:8.2-apache

RUN apt-get install php-xml
RUN docker-php-ext-install pdo pdo_mysql
RUN a2enmod rewrite

COPY --from=build /kp_esportes/ /var/www/html/

EXPOSE 80