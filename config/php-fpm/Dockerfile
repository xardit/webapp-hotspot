FROM php:7.3-fpm-alpine

# Install requirements
RUN apk add --no-cache zlib-dev imap-dev openssl-dev krb5-dev libjpeg-turbo-dev libpng-dev libzip-dev \
	&& docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
	&& docker-php-ext-configure gd --with-png-dir=/usr --with-jpeg-dir=/usr \
	&& docker-php-ext-install gd mysqli opcache zip imap mbstring pdo pdo_mysql

COPY www.conf /usr/local/etc/php-fpm.d/www.conf

WORKDIR /app

CMD ["php-fpm"]