FROM php:8.2-fpm-alpine

ENV PHPUSER=symfony
ENV PHPGROUP=symfony

ENV UID=1000
ENV GID=1000

RUN addgroup -g 1006 --system symfony
RUN adduser -G www-data --system -D -s /bin/sh -u 1006 symfony

# RUN ls /usr/local/etc/php-fpm.d/

# RUN sed -i "s/user = www-data/user = ${PHPUSER}/g" /usr/local/etc/php-fpm.d/www.conf
# RUN sed -i "s/group = www-data/group = ${PHPGROUP}/g" /usr/local/etc/php-fpm.d/www.conf

# RUN apk update && apk add --no-cache \
#     git \
#     unzip \
#     wget \
#     icu-dev \
#     && docker-php-ext-install pdo pdo_mysql intl \
#     && echo 'extension=intl.so' > /usr/local/etc/php/conf.d/docker-php-ext-intl.ini

RUN apk update && apk add --no-cache \
    acl \
    fcgi \
    file \
    gettext \
    git \
    icu \
    icu-libs \
    icu-dev \
    libzip-dev \
    unzip \
    wget \
    gcc \
    g++ \
    make \
    autoconf

# Installer les extensions PHP
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_mysql opcache \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && echo 'extension=intl.so' > /usr/local/etc/php/conf.d/docker-php-ext-intl.ini

RUN mkdir -p /var/www/html/public
# Add packages for symfony

CMD ["php-fpm"]