FROM thecodingmachine/php:rc3167271323-8.1-v4-fpm as php

COPY --from=composer:2.3.7 --chown=docker:docker /usr/bin/composer /usr/bin/composer
COPY --from=thecodingmachine/php:rc3167271323-8.1-v4-fpm /usr/bin/php /usr/local/sbin/php

WORKDIR /application

RUN composer install --no-scripts --no-interaction --prefer-dist --optimize-autoloader --ignore-platform-reqs
RUN composer dump-autoload --optimize --classmap-authoritative

FROM nginx:alpine AS nginx
