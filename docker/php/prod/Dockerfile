FROM php:8.1.6-fpm-alpine as php
WORKDIR /application

COPY --from=composer:2.3.7 /usr/bin/composer /usr/bin/composer

RUN apk --update upgrade \
    && apk add --no-cache autoconf automake make gcc g++ bash icu-dev libzip-dev rabbitmq-c rabbitmq-c-dev \
    && docker-php-ext-install -j$(nproc) \
        bcmath \
        intl \
        zip \
        pdo_mysql

COPY ./ /application

EXPOSE 9000

FROM nginx:alpine AS nginx
COPY --from=php /application /application
COPY ./docker/nginx/nginx.conf /etc/nginx/nginx.conf

EXPOSE 80

CMD ["nginx", "-g", "daemon off;"]

FROM mysql as mysql
