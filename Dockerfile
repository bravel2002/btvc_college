# Use official PHP image with Apache
FROM php:8.2-apache

# Copy project files to Apache web root
COPY . /var/www/html/

# Fix permissions so Apache can read files
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html

# Enable mod_rewrite
RUN a2enmod rewrite

# Expose port 80
EXPOSE 80

# Start Apache in foreground
CMD ["apache2-foreground"]
