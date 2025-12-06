# Use official PHP Apache image
FROM php:8.2-apache

# Set Railway PORT environment variable
ENV PORT=8080

# Tell Apache to listen on Railway's port
RUN sed -i "s/80/\${PORT}/g" /etc/apache2/ports.conf
RUN sed -i "s/:80/:${PORT}/g" /etc/apache2/sites-available/000-default.conf

# Copy all project files to Apache web root
COPY . /var/www/html/

# Enable Apache mod_rewrite (optional)
RUN a2enmod rewrite

# Install MySQL extensions
RUN docker-php-ext-install mysqli pdo pdo_mysql

# Fix permissions
RUN chown -R www-data:www-data /var/www/html

# Expose the port
EXPOSE ${PORT}

# Run Apache in foreground
CMD ["apache2-foreground"]
