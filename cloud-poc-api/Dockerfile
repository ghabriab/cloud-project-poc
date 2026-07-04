FROM php:8.2-apache

# Install required tools and PHP extensions
RUN apt-get update && apt-get install -y libzip-dev zip unzip \
    && docker-php-ext-install pdo pdo_mysql zip

# Enable Apache mod_rewrite for Symfony routing
RUN a2enmod rewrite

# Update Apache DocumentRoot to point to Symfony's public directory
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf

# Set working directory
WORKDIR /var/www/html

# Copy project files into the container
COPY . .

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Allow Composer to run as root during the build process
ENV COMPOSER_ALLOW_SUPERUSER=1

# Install PHP dependencies (Single, optimized install)
RUN composer install --no-dev --no-scripts --no-interaction --optimize-autoloader

# Create the var directory explicitly and set final permissions for Apache
RUN mkdir -p /var/www/html/var && chown -R www-data:www-data /var/www/html

# DO NOT add "USER www-data" here.
# Apache must start as root to bind to port 80, and will automatically run child processes as www-data.
