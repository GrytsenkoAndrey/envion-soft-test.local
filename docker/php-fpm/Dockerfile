FROM php:8.1-fpm

WORKDIR /var/www

RUN apt-get update && apt-get install -y \
    build-essential \
    mariadb-client \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    libzip-dev \
    libpq-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl


RUN curl -sL https://deb.nodesource.com/setup_16.x | bash \
    && apt-get install -y nodejs

RUN apt-get install -y cron && \
    apt-get install -y supervisor

RUN apt-get clean && rm -rf /var/lib/apt/lists/*

RUN docker-php-ext-install pdo_mysql zip exif pcntl pdo_pgsql fileinfo bcmath
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd
RUN apt-get update && pecl install xdebug-3.1.0 \
    && docker-php-ext-enable xdebug

RUN apt-get install -y libcurl4-openssl-dev pkg-config libssl-dev

RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

RUN groupadd -g 1000 docker && useradd -u 1000 -g docker -m docker  \
    && su docker

COPY . /var/www

COPY --chown=docker:docker . /var/www

CMD php-fpm

EXPOSE 9000
