# Use an official PHP image with Apache
FROM php:8.1-apache

# Copy your PHP application into the container
COPY . /var/www/html/

# Set the working directory
WORKDIR /var/www/html/

# Expose the default HTTP port
EXPOSE 80

# Start the Apache server
CMD ["apache2-foreground"]
