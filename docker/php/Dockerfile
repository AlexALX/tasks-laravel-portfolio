FROM php:8.4-fpm-alpine AS base

RUN apk add --no-cache \
    curl-dev \
    libcurl \
    oniguruma-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql mbstring curl

COPY ./docker/php/php.ini /usr/local/etc/php/conf.d/custom.ini

# self contained profile
FROM base AS self

COPY . .

# this will not keep node-modules in layer
RUN apk add --no-cache nodejs npm \
    && npm install && npm run build \
    && rm -rf node_modules \
    && apk del nodejs npm

# this will also not keep composer in layer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer \
    && composer install --no-dev --optimize-autoloader \
    && rm /usr/local/bin/composer

WORKDIR /var/www/html

EXPOSE 9000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=9000"]
# end of self contained profile

# dev profile
FROM base AS dev

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN apk add --update nodejs npm

WORKDIR /var/www/html

EXPOSE 9000
# end of dev profile