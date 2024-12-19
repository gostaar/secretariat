FROM php:8.2-fpm

RUN apt-get update && apt-get install -y \
    nginx \
    libicu-dev \
    libzip-dev \
    git \
    wget \
    unzip \
    ca-certificates \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /usr/local/etc/php/conf.d

RUN echo "zend.enable_gc = 0" > /usr/local/etc/php/conf.d/disable-zend.ini \
    && echo "max_execution_time = 30" >> /usr/local/etc/php/conf.d/disable-zend.ini \
    && echo "max_input_time = 60" >> /usr/local/etc/php/conf.d/disable-zend.ini

RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_mysql \
    && pecl install apcu \
    && docker-php-ext-enable apcu \
    && echo 'extension=intl.so' > /usr/local/etc/php/conf.d/docker-php-ext-intl.ini

# RUN if [ -f /usr/local/etc/php/conf.d/opcache.ini ]; then mv /usr/local/etc/php/conf.d/opcache.ini /usr/local/etc/php/conf.d/opcache.ini-disabled; fi


COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/default.conf /etc/nginx/sites-available/default

COPY . /var/www/html/

RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

EXPOSE 80

CMD php-fpm -D && nginx -g 'daemon off;'

