FROM php:7.3-fpm-alpine

# Install requirements
RUN docker-php-ext-install mysqli zip mbstring

COPY www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /var/www

CMD ["php-fpm"]