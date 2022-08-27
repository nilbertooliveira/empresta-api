FROM php:8-fpm

# Copy composer.lock and composer.json
#COPY composer.lock composer.json /var/www/

# Set working directory
WORKDIR /var/www

# Install dependencies
RUN rm /etc/apt/preferences.d/no-debian-php && \
    apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg62-turbo-dev \
    libfreetype6-dev \
    locales \
    zip \
    jpegoptim optipng pngquant gifsicle \
    vim \
    unzip \
    git \
    curl\
    libzip-dev\
    iputils-ping\
    telnet\
    net-tools\
    libonig-dev\
    libxml2-dev\
    php-soap\
    supervisor


RUN pecl install xdebug redis && docker-php-ext-enable xdebug redis

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install extensions
RUN docker-php-ext-install pdo_mysql mbstring zip exif pcntl soap
RUN docker-php-ext-configure gd --with-jpeg=/usr/include/ --with-freetype=/usr/include/
RUN docker-php-ext-install gd

# Configurações do supervisor
RUN mkdir -p "/etc/supervisor/logs"
RUN chmod -R 777 "/etc/supervisor/logs"
ADD ./docker/supervisor/supervisord.conf /etc/supervisor/conf.d/worker.conf

# Install composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer #--version=1.10.26

# Add user for laravel application
RUN groupadd -g 1000 www
RUN useradd -u 1000 -ms /bin/bash -g www www

# Copy existing application directory contents
#COPY . /var/www

# Copy existing application directory permissions
COPY --chown=www:www . /var/www

# Change current user to www
USER www

# Expose port 9000 and start php-fpm server
EXPOSE 9000
CMD ["php-fpm"]
