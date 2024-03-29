FROM php:8.2-fpm

# Set working directory
WORKDIR /app
USER root

# Install dependencies
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    libonig-dev \
    git \
    curl \
    libzip-dev \
    libwebp-dev \
    jpegoptim \
    optipng \
    pngquant \
    gifsicle

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN apt-get update && apt-get install -y libpq-dev
RUN docker-php-ext-install pdo pdo_pgsql mbstring zip exif pcntl bcmath
RUN docker-php-ext-configure gd --with-freetype=/usr/include/ --with-jpeg=/usr/include/ --with-webp=/usr/include/
RUN docker-php-ext-install gd
RUN if [ ${INSTALL_PHPREDIS} = true ]; then \
    pecl install --force redis && rm -rf /tmp/pear && docker-php-ext-enable redis \
;fi
# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
COPY .. /app

# Copy existing application directory permissions
COPY --chown=www:www .. /app

# Change current user to www
USER www

RUN echo 'alias a="/usr/local/bin/php artisan"' >> ~/.bashrc

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]