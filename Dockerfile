FROM php:8.2-apache

# Install dependencies (zip, unzip, git, mysql extensions)
RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    unzip \
    && docker-php-ext-install pdo_mysql zip

# Disable ALL MPM modules first to prevent conflicts
RUN a2dismod mpm_event mpm_worker mpm_prefork || true

# Enable ONLY mpm_prefork (required for PHP)
RUN a2enmod mpm_prefork

# Enable Apache mod_rewrite
RUN a2enmod rewrite

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
