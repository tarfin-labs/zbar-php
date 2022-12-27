FROM php:7.2-alpine

RUN apk add --no-cache \
    autoconf \
    g++ \
    pkgconfig \
    imagemagick \
    imagemagick-libs \
    imagemagick-dev \
    make \
    zbar && \
    pecl install \
    imagick && \
    docker-php-ext-enable \
    imagick && \
    php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');" && \
    php composer-setup.php && \
    php -r "unlink('composer-setup.php');" && \
    mv composer.phar /usr/local/bin/composer
