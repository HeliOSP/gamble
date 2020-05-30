FROM php:7.2.7-fpm-alpine3.7
RUN apk update; \
    apk upgrade;
RUN docker-php-ext-install mysqli pdo_mysql
# Project-specific ini settings
COPY ./php-ini-overrides.ini /usr/local/etc/php/conf.d/