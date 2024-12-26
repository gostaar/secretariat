FROM php:8.3-fpm-alpine
RUN docker-php-ext-install mysqli pdo pdo_mysql

# RUN apt-get update && apt-get install -y \
RUN apk add --no-cache \
    libressl \
    libressl-dev \
    autoconf \
    bash \
    g++ \
    make \
    && pecl install redis \
    && docker-php-ext-enable redis

RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

RUN apk add --no-cache ca-certificates && update-ca-certificates