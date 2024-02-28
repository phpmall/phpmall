FROM php:8.2.16-apache-bullseye

RUN sed -i 's/deb.debian.org/mirrors.ustc.edu.cn/g' /etc/apt/sources.list

# install composer
COPY --from=composer:latest /usr/bin/composer /usr/local/bin/composer

## install GD extension
RUN apt-get update && \
apt-get install -y libfreetype6-dev libjpeg62-turbo-dev libpng-dev && \
docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ && \
docker-php-ext-install gd

## install zip extension
RUN apt-get install -y libzip-dev zip && docker-php-ext-install zip

## install pcntl extension
RUN docker-php-ext-install pcntl

## install mysql extension
RUN docker-php-ext-install pdo_mysql

## install redis extension
RUN pecl install redis && docker-php-ext-enable redis

## enable rewrite module
RUN ln -s /etc/apache2/mods-available/rewrite.load /etc/apache2/mods-enabled/rewrite.load
