FROM php:7.4-fpm

# Copy composer.lock and composer.json into the working directory
# COPY ../composer.lock ../composer.json /var/www/html/

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

# Install extensions for php
RUN docker-php-ext-install mbstring zip exif pcntl
RUN docker-php-ext-configure pgsql -with-pgsql=/usr/local/pgsql
RUN docker-php-ext-install pdo pdo_pgsql
RUN docker-php-ext-configure gd --with-freetype --with-jpeg
RUN docker-php-ext-install gd

# Install composer (php package manager)
# RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents to the working directory
COPY . /var/www/html

RUN cp .env.docker .env

# Assign permissions of the working directory to the 1000 user
RUN chown -R 1000:1000 \
        /var/www/html/storage \
        /var/www/html/bootstrap/cache \
        /var/www/html/storage/logs
RUN chmod o+w /var/www/html/storage -R

RUN cd /var/www/html && composer install
RUN php artisan key:generate
RUN php artisan storage:link
RUN php artisan migrate
RUN php artisan db:seed

# Expose port 9000 and start php-fpm server (for FastCGI Process Manager)
EXPOSE 9000
CMD ["php-fpm"]
