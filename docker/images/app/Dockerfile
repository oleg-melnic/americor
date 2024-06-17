FROM php:8.1-fpm-alpine3.16

WORKDIR /app
COPY . .

RUN apk update
RUN apk add --no-cache --virtual .build-deps g++ make gnupg autoconf \
            postgresql-dev openssl-dev libzip-dev libpng-dev libxml2-dev unixodbc-dev \
            composer supervisor nginx git zip bash nano \
        && pecl install xdebug-3.1.4 \
        && docker-php-ext-enable xdebug

RUN docker-php-ext-install pdo pdo_pgsql zip gd xml opcache

RUN apk add php-ctype php-tokenizer php-xml php-pdo php-session php-dom php-simplexml

RUN mkdir -p /var/run/php && mkdir -p /app/logs
COPY ./docker/conf/supervisord.conf /etc/supervisor/supervisord.conf
COPY ./docker/conf/crontab  /etc/crontabs/root
COPY ./docker/conf/server.conf /etc/nginx/nginx.conf
COPY ./docker/conf/nginx.conf /etc/nginx/conf.d/default.conf
COPY ./docker/conf/php.ini "$PHP_INI_DIR/php.ini"
COPY ./docker/conf/php-fpm.ini  /usr/local/etc/php-fpm.d/www.conf
COPY ./docker/conf/remote-xdebug.ini /usr/local/etc/php/conf.d/remote-xdebug.ini

RUN cp /usr/local/bin/php /usr/bin/php8
#RUN composer install --no-ansi --no-interaction

EXPOSE 80

CMD ["/bin/sh", "/app/docker/php-entrypoint.sh"]
