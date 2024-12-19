FROM php:8.2-fpm

# Mise à jour des paquets et installation des dépendances
RUN apt-get update && apt-get install -y \
    nginx \
    libicu-dev \
    libzip-dev \
    git \
    wget \
    unzip \
    ca-certificates \
    redis-server \  # Ajout de Redis
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# Création du répertoire pour les configurations PHP
RUN mkdir -p /usr/local/etc/php/conf.d

# Configuration PHP
RUN echo "zend.enable_gc = 0" > /usr/local/etc/php/conf.d/disable-zend.ini \
    && echo "max_execution_time = 30" >> /usr/local/etc/php/conf.d/disable-zend.ini \
    && echo "max_input_time = 60" >> /usr/local/etc/php/conf.d/disable-zend.ini

# Installation des extensions PHP
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl pdo pdo_mysql \
    && pecl install apcu \
    && pecl install redis \  
    && docker-php-ext-enable apcu redis \  # Activation des extensions
    && echo 'extension=intl.so' > /usr/local/etc/php/conf.d/docker-php-ext-intl.ini

# Copie de la configuration nginx
COPY nginx/nginx.conf /etc/nginx/nginx.conf
COPY nginx/default.conf /etc/nginx/sites-available/default

# Copie du code de l'application
COPY . /var/www/html/

# Permissions sur le code
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html

# Nettoyage
RUN rm -rf /var/lib/apt/lists/* /tmp/* /var/tmp/*

# Exposition du port 80
EXPOSE 80

# Commande pour démarrer PHP-FPM et Nginx
CMD php-fpm -D && nginx -g 'daemon off;'
