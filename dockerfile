# Start from the official PHP-FPM image
FROM php:8.3-fpm

# Define the build argument for the group ID
ARG WWWGROUP=1000

# Create the 'sail' group and user with the specified IDs
RUN groupadd -g ${WWWGROUP:-1000} sail || true \ && useradd -ms /bin/bash -g sail -u 1337 sail

# Install PHP extensions for Laravel
RUN docker-php-ext-install pdo pdo_mysql

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Define the working directory
WORKDIR /the/workdir/path

# Copy the application files into the container
COPY . .

# Run Composer installation
RUN composer install --no-dev --optimize-autoloader

# Set file permissions for the web server user
RUN chown -R www-data:www-data /the/workdir/path /var/www/storage

# Start PHP-FPM when the container runs
CMD ["php-fpm"]