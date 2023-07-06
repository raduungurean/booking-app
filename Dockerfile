# Base image
FROM php:8.1-fpm

# Set working directory
WORKDIR /var/www/html

# Install dependencies
RUN apt-get update && apt-get install -y \
    libonig-dev \
    libzip-dev \
    unzip \
    zip

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring zip

# Install Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Copy project files
COPY . .

# Copy startup script
COPY start-container.sh /usr/local/bin/
RUN chmod +x /usr/local/bin/start-container.sh

# Expose port
EXPOSE 9000

# Start PHP-FPM using the startup script
CMD ["start-container.sh"]
