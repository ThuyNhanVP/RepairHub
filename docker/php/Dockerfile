FROM node:22-alpine AS frontend

WORKDIR /var/www/html

COPY package*.json ./
RUN npm install

COPY . .
RUN npm run build

FROM php:8.3-fpm-alpine

WORKDIR /var/www/html

RUN apk add --no-cache \
        bash \
        curl \
        git \
        icu-dev \
        libzip-dev \
        oniguruma-dev \
        unzip \
        zip \
        mysql-client \
    && docker-php-ext-install \
        pdo_mysql \
        intl \
        mbstring \
        zip \
        bcmath \
        pcntl

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
COPY --from=frontend /var/www/html/public/build /opt/repairhub-build/public/build
COPY docker/php/php.ini /usr/local/etc/php/conf.d/99-repairhub.ini
COPY --chmod=755 docker/php/entrypoint.sh /usr/local/bin/repairhub-entrypoint.sh
RUN sed -i 's/\r$//' /usr/local/bin/repairhub-entrypoint.sh

EXPOSE 9000
ENTRYPOINT ["/usr/local/bin/repairhub-entrypoint.sh"]
CMD ["php-fpm"]
