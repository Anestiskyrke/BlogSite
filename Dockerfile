FROM php:8.1-apache

ADD etc/000-default.conf $APACHE_CONFDIR/sites-available/000-default.conf
ADD etc/php.ini /usr/local/etc/php/conf.d/php.ini

RUN curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer

RUN apt-get update \
    && apt-get install -y -f apt-transport-https \
        libicu-dev \
        libfreetype6-dev \
        libjpeg62-turbo-dev \
        libpng-dev \
        libonig-dev \
        libxml2-dev \
        acl \
        libzip-dev \
        cmdtest \
        npm \
    && docker-php-ext-install \
        intl \
        opcache \
        pdo \
        pdo_mysql \
        zip \
        gd \
        xml \
        dom \
        mbstring \
        soap \
    && a2enmod rewrite \
    && mkdir -p /var/www/html/var \
    && mkdir -p /etc/cron.d/
