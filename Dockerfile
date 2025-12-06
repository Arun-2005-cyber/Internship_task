# Use official PHP Apache image
FROM php:8.2-apache

# Copy all project files to Apache web root
COPY . /var/www/html/

# Enable Apache mod_rewrite (optional)
RUN a2enmod rewrite

# Install MySQL extension
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Set permissions
RUN chown -R www-data:www-data /var/www/html

EXPOSE 80
