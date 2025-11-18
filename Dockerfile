# Use official PHP image with Apache
FROM php:8.2-apache

# Install MySQLi and other necessary extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Copy project files to Apache web root
COPY . /var/www/html/

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
