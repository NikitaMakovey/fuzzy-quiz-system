FROM php:8.2-fpm

WORKDIR /var/www

# Install linux packages
RUN apt-get update && apt-get install -y --no-install-recommends \
    sudo \
    openssl \
    git \
    zip \
    cron \
    libpq-dev \
    libzip-dev \
    gnupg2 \
    libicu-dev \
    libpng-dev \
    libc-client-dev \
    libkrb5-dev \
    libjpeg-dev \
    bash-completion \
    nano \
    && rm -r /var/lib/apt/lists/*

#intsall composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer && composer --version

# Install and configure php extensions
RUN docker-php-ext-configure intl \
    && docker-php-ext-install intl \
    && docker-php-ext-install pdo_pgsql bcmath gd zip \
    && pecl install xdebug \
    && docker-php-ext-enable xdebug \
    && PHP_OPENSSL=yes docker-php-ext-configure imap --with-kerberos --with-imap-ssl \
    && docker-php-ext-install imap \
    && pecl install ast \
    && docker-php-ext-enable ast \
    && docker-php-ext-configure gd --with-jpeg=/usr/include/ --enable-gd \
    && docker-php-ext-install gd \
    && docker-php-ext-configure pcntl --enable-pcntl \
    && docker-php-ext-install pcntl \
    && docker-php-ext-install sockets

COPY . /var/www

RUN chown -R www-data:www-data /var/www
