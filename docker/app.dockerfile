FROM php:8.2-fpm

RUN apt update && apt -y upgrade 

RUN apt install -y --no-install-recommends curl apt-utils mcrypt libpq-dev libz-dev libfreetype6-dev zlib1g-dev libssl-dev libmcrypt-dev libxml2-dev libxslt-dev libzip-dev libbz2-dev libmcrypt-dev nodejs cron\ 
  && docker-php-ext-install pcntl \
  && docker-php-ext-configure gd \
  && docker-php-ext-install zip \
  && docker-php-ext-install bz2 \
  && docker-php-ext-install pdo_mysql \
  && docker-php-ext-install mysqli \
  && docker-php-ext-install xsl

RUN pecl install xdebug \
  && docker-php-ext-enable xdebug

COPY ./config/xdebug.ini /usr/local/etc/php/conf.d/xdebug.ini

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer