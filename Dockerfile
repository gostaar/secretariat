FROM php:8.3-fpm-alpine
RUN docker-php-ext-install mysqli pdo pdo_mysql

RUN apk add --no-cache \
    libressl \
    libressl-dev \
    autoconf \
    bash \
    g++ \
    make \
    ca-certificates \
    icu-dev && update-ca-certificates \
    && pecl install redis \
    && docker-php-ext-enable redis \
    && docker-php-ext-install intl

RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*
