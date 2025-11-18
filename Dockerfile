# Use official PHP image with Apache
FROM php:8.2-apache

# Copy project files to Apache web root
COPY . /var/www/html/

# Give Apache permissions to read files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Enable Apache mod_rewrite (optional, useful if you use .htaccess)
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
