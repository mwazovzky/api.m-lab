FROM php:7.4-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y unzip libpng-dev libjpeg-dev
RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install gd
RUN docker-php-ext-install pdo_mysql

COPY . ./
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN php /usr/local/bin/composer install
