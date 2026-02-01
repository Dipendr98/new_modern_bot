FROM php:8.2-apache

# Install dependencies and PHP extensions
RUN apt-get update && apt-get install -y \
    libzip-dev \
    libpng-dev \
    libjpeg-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-jpeg \
    && docker-php-ext-install pdo_mysql zip gd \
    && apt-get clean \
    && rm -rf /var/lib/apt/lists/*

# Enable Apache modules (mod_rewrite for .htaccess)
RUN a2enmod rewrite

# Enable Apache AllowOverride for .htaccess support
RUN sed -i '/<Directory \/var\/www\/>/,/<\/Directory>/ s/AllowOverride None/AllowOverride All/' /etc/apache2/apache2.conf

# Copy application files
COPY . /var/www/html/

# Set working directory
WORKDIR /var/www/html

# Permissions (Ensure Apache can write to uploads/logs if needed, though volume mount handles most)
RUN chown -R www-data:www-data /var/www/html

# Fix for DirectoryIndex if needed, or rely on .htaccess
# (Standard PHP Apache image handles index.php by default)

# Exposure
EXPOSE 80
