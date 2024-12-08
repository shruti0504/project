# Use official PHP image with Apache
FROM php:8.0-apache

# Install Composer (dependency manager for PHP)
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer

# Set the working directory to /var/www/html
WORKDIR /var/www/html

# Copy your PHP project files into the container
COPY . /var/www/html/

# Install PHP dependencies using Composer
RUN composer install

# Expose port 80 to the outside world (default for web servers)
EXPOSE 80

# Start Apache web server
CMD ["apache2-foreground"]
