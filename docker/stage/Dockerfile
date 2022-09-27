FROM php:8.1-fpm

ENV PHPREDIS_VERSION 5.3.7

# Set working directory
WORKDIR /var/www

# Add docker php ext repo
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions \
    && sync \
    && install-php-extensions \
    mbstring \
    pdo_mysql \
    zip \
    exif \
    pcntl \
    gd \
    memcached

# Install dependencies
RUN apt-get update \
    && apt-get install -y \
        build-essential \
        libpng-dev \
        libjpeg62-turbo-dev \
        libfreetype6-dev \
        locales \
        zip \
        jpegoptim optipng pngquant gifsicle \
        unzip \
        git \
        curl \
        lua-zlib-dev \
        libmemcached-dev \
        nginx \
        nano \
        supervisor \
    && pecl install \
        xdebug \
    && mkdir -p /usr/src/php/ext/redis \
    && curl -L https://github.com/phpredis/phpredis/archive/$PHPREDIS_VERSION.tar.gz | tar xvz -C /usr/src/php/ext/redis --strip 1 \
    && echo 'redis' >> /usr/src/php-available-exts \
    && docker-php-ext-install \
        redis \
        opcache \
    && docker-php-ext-enable \
        opcache \
        xdebug \
        redis

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Add user for laravel application
RUN groupadd -g 1000 www && useradd -u 1000 -ms /bin/bash -g www www

# Copy code to /var/www
COPY --chown=www:www-data . /var/www

# add root to www group
RUN chmod -R ug+w /var/www/storage

# Copy nginx/php/supervisor configs
RUN cp docker/stage/supervisor.conf /etc/supervisord.conf
RUN cp docker/stage/php.ini /usr/local/etc/php/conf.d/app.ini
RUN cp docker/stage/nginx.conf /etc/nginx/sites-enabled/default
RUN cp .env.stage .env

# PHP Error Log Files
RUN mkdir /var/log/php
RUN touch /var/log/php/errors.log && chmod 777 /var/log/php/errors.log

# Supervisor Log Files (Queues)
RUN mkdir -p /var/log/casino/backend

RUN touch /var/log/casino/backend/queue-default-error.log
RUN chmod 777 /var/log/casino/backend/queue-default-error.log

RUN touch /var/log/casino/backend/queue-default-access.log
RUN chmod 777 /var/log/casino/backend/queue-default-access.log

RUN touch /var/log/casino/backend/queue-withdrawal-error.log
RUN chmod 777 /var/log/casino/backend/queue-withdrawal-error.log

RUN touch /var/log/casino/backend/queue-withdrawal-access.log
RUN chmod 777 /var/log/casino/backend/queue-withdrawal-access.log

# Deployment steps
RUN chmod +x /var/www/docker/stage/run.sh

EXPOSE 80
ENTRYPOINT ["/var/www/docker/stage/run.sh"]
