FROM php:8.2-cli

# Install extensions
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    libzip-dev \
    libcurl4-openssl-dev \
    libonig-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) gd pdo_mysql zip curl mbstring \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /app

# Copy application
COPY . .

# Expose port
EXPOSE 8080

# Start server - use shell form for variable expansion
CMD php -S 0.0.0.0:${PORT:-8080} -t . router.php
