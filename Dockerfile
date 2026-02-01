FROM php:8.2-apache

# Install dependencies (zip, unzip, git, mysql extensions)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Disable conflicting MPM modules (fix for "More than one MPM loaded" error)
RUN a2dismod mpm_event mpm_worker

# Enable Apache mod_rewrite and mpm_prefork
RUN a2enmod rewrite mpm_prefork

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
