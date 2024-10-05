FROM php:8.2-fpm

WORKDIR /var/www/html

RUN apt-get update -y && apt-get upgrade -y \
    && apt-get install -y gnupg2 curl gawk iproute2 iputils-ping procps sudo vim git htop libgtk2.0-0 libgtk-3-0 \
    libgbm-dev libnotify-dev libgconf-2-4 libnss3 libxss1 libasound2 libxtst6 unzip wait-for-it xauth xvfb zip nodejs npm

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN npm install -g yarn

ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/
RUN chmod uga+x /usr/local/bin/install-php-extensions && sync \
    && install-php-extensions gd intl mysqli opcache pdo_mysql sysvsem xdebug zip

## CONFIGURE
ENV TZ=Europe/Prague
ENV OPCACHE_ENABLE=1
ENV OPCACHE_MEMORY_CONSUMPTION=192
ENV OPCACHE_INTERNED_STRINGS_BUFFER=16
ENV OPCACHE_MAX_ACCELERATED_FILES=10000
ENV OPCACHE_MAX_WASTED_PERCENTAGE=10
ENV OPCACHE_VALIDATE_TIMESTAMPS=1
ENV OPCACHE_REVALIDATE_FREQ=0