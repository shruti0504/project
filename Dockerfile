# Use the official PHP image with Apache
FROM php:8.0-apache

# Install any required PHP extensions (if needed)
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy the application files into the container
WORKDIR /var/www/html
COPY . /var/www/html/

# Expose port 80 to the outside world (default for web servers)
EXPOSE 80

# Start Apache in the foreground
CMD ["apache2-foreground"]
