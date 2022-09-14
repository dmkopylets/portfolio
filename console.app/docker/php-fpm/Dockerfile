FROM php:8.1.8-fpm-alpine AS foxminded_php818_fpm_alpine
MAINTAINER uncle.dimaz <uncle.dima.k@gmail.com>
LABEL mainterner="uncle.dimaz <uncle.dima.k@gmail.com>"
LABEL description="Alpine based image with php8.1-fpm"

ENV TZ="Europe/Kiev"
RUN ln -snf /usr/share/zoneinfo/$TZ /etc/localtime && echo $TZ > /etc/timezone

RUN apk update && apk upgrade && apk add --no-cache bash

# install the PHP extensions we need
RUN set -eux; \
    \
    apk add --no-cache --virtual .build-deps \
    coreutils \
    freetype-dev \
    libjpeg-turbo-dev \
    libpng-dev \
    libzip-dev \
    postgresql-dev \
    git \
    nodejs \
    npm \
    ; \
    \
    docker-php-ext-configure gd \
    --with-freetype \
    --with-jpeg=/usr/include \
    ; \
    \
    docker-php-ext-install -j "$(nproc)" \
    gd \
    opcache \
    pdo_pgsql \
    intl \
    zip \
    ; \
    \
    runDeps="$( \
    scanelf --needed --nobanner --format '%n#p' --recursive /usr/local \
        | tr ',' '\n' \
        | sort -u \
        | awk 'system("[ -e /usr/local/lib/" $1 " ]") == 0 { next } { print "so:" $1 }' \
    )"; \
    apk add --no-network --virtual .drupal-phpexts-rundeps $runDeps; \
    apk del --no-network .build-deps

RUN { \
    echo 'opcache.memory_consumption=128'; \
    echo 'opcache.interned_strings_buffer=8'; \
    echo 'opcache.max_accelerated_files=4000'; \
    echo 'opcache.revalidate_freq=60'; \
    echo 'opcache.fast_shutdown=1'; \
    } > /usr/local/etc/php/conf.d/opcache-recommended.ini

RUN set -x \
   # Install development environment
    && apk-install \
        make \
        autoconf \
        g++ \
    && pecl install xdebug \
    && apk del -f --purge \
        autoconf \
        g++ \
        make \
    docker-php-ext-enable xdebug \
    ; \

RUN { \
    echo 'xdebug.mode=coverage'; \
    echo 'xdebug.remote_handler=dbgp'; \
    echo 'xdebug.extended_info = 1'; \
    echo 'xdebug.idekey = "PHPSTORM"' \
    }  > /usr/local/etc/php/conf.d/docker-php-ext-xdebug.ini \
    ; \

RUN set -eux; \
    \
    apk del $DEPS; \
    rm -rf /tmp/pear

ARG PUID=1000
ARG PGID=1000
RUN apk --no-cache add shadow && \
    groupmod -o -g ${PGID} www-data && \
    usermod -o -u ${PUID} -g www-data www-data

WORKDIR /var/www

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

USER www-data

CMD php-fpm

EXPOSE 9000
