# Use the official PHP image as a base image
FROM php:8.3-fpm

# Set working directory
WORKDIR /var/www/html

# Install system dependencies
RUN apt-get update && apt-get install -y \
  build-essential \
  libpng-dev \
  libjpeg-dev \
  libfreetype6-dev \
  locales \
  zip \
  jpegoptim optipng pngquant gifsicle \
  vim \
  unzip \
  git \
  curl \
  libonig-dev \
  libxml2-dev \
  libzip-dev

# Clear cache
RUN apt-get clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy existing application directory contents
COPY . /var/www/html

# Copy existing application directory permissions
COPY --chown=www-data:www-data . /var/www/html

# Set correct permissions
RUN chown -R www-data:www-data /var/www/html \
  && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80

# Expose port for Vite (Frontend)
# EXPOSE 5173


# Start PHP-FPM and Nginx server
CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=80"]
