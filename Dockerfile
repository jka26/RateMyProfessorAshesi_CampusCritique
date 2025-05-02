# Use the official PHP-Apache image
FROM php:8.2-apache

# Enable PHP extensions if needed (like mysqli)
RUN docker-php-ext-install mysqli

# Copy all project files into the web server directory
COPY . /var/www/html/

# Give correct permissions (optional but recommended)
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html

# Expose port 80
EXPOSE 80
