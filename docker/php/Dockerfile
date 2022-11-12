FROM thecodingmachine/php:rc3167271323-8.1-v4-fpm as php

COPY --from=composer:2.3.7 --chown=docker:docker /usr/bin/composer /usr/bin/composer

WORKDIR /application

FROM nginx:1.23.2-alpine AS nginx
