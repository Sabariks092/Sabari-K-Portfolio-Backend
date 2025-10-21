# Use official PHP image with Apache
FROM php:8.2-apache

# Copy project files into the container
COPY . /var/www/html/

# Expose port 80 (default web server port)
EXPOSE 80
