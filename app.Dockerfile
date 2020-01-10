ARG PHP_VERSION=7-fpm
FROM php:$PHP_VERSION

RUN apt-get update \
    && apt-get install -y libmcrypt-dev libicu-dev \
    && pecl install mcrypt-1.0.3 \
    && docker-php-ext-enable mcrypt \
    && docker-php-ext-install pdo_mysql intl

RUN echo "\nphp_flag[expose_php] = off" >> /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www
