FROM php:8.1-fpm

# Add docker php ext repo
ADD https://github.com/mlocati/docker-php-extension-installer/releases/latest/download/install-php-extensions /usr/local/bin/

# Install php extensions
RUN chmod +x /usr/local/bin/install-php-extensions && sync && \
    install-php-extensions pdo_mysql zip exif pcntl gd memcached

# Install system dependencies
RUN apt-get update

# Install dependencies
RUN apt-get install -y build-essential
RUN apt-get install -y libpng-dev
RUN apt-get install -y libjpeg62-turbo-dev
RUN apt-get install -y libfreetype6-dev
RUN apt-get install -y locales
RUN apt-get install -y zip
RUN apt-get install -y jpegoptim optipng pngquant gifsicle
RUN apt-get install -y unzip
RUN apt-get install -y git
RUN apt-get install -y net-tools
RUN apt-get install -y vim
RUN apt-get install -y curl
RUN apt-get install -y lua-zlib-dev
RUN apt-get install -y libmemcached-dev

# Install supervisor
RUN apt-get install -y supervisor

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

WORKDIR /var/www/backend

COPY ./docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf
COPY ./docker/php.ini /usr/local/etc/php/conf.d/app.ini
COPY ./docker/start-container /usr/local/bin/start-container

RUN chmod +x /usr/local/bin/start-container

ENTRYPOINT ["start-container"]