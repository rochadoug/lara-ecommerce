# FROM php:7.4-fpm AS php-build
FROM php:7.4-fpm AS php-fpm

# Set working directory
WORKDIR /var/www/html/

# Install dependencies for the operating system software
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    libzip-dev \
    unzip \
    git \
    libonig-dev \
    curl \
    libpq-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install composer (php package manager)
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

FROM php-fpm AS php-dockerize

# Install extensions for php
RUN docker-php-ext-install mbstring zip exif pcntl
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Set permissions for common user
FROM php-dockerize AS php-copy-project

# Copy existing application directory contents to the working directory
COPY . /var/www/html

# Create system user to run Composer and Artisan Commands
ARG WWWGUID
RUN useradd -ms /bin/bash -u $WWWGUID -G www-data,root laravel

# Assign permissions of the working directory to the 1000 user
RUN chown -R laravel:laravel \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/www/html/storage/logs
RUN chmod o+w /var/www/html/storage -R
RUN rm -rf public/storage

USER $WWWGUID:$WWWGUID

# Expose port 9000 and start php-fpm server (for FastCGI Process Manager)
EXPOSE 9000

CMD ["bash", "-c" , "cp /var/www/html/.env.docker /var/www/html/.env && composer install --no-plugins && php artisan key:generate && rm -rf public/storage && php artisan storage:link && php-fpm"]
