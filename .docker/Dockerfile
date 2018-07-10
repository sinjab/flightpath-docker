FROM php:5.6.36-apache

MAINTAINER Khaldoon Sinjab

# Copy public directory
COPY public /srv/app

# Copy virtual hosts file
COPY .docker/vhost.conf /etc/apache2/sites-available/000-default.conf

# Install necessary packages
RUN apt-get update && apt-get install -y \
		libpng-dev

# Enable rewrite module for Apache
RUN a2enmod rewrite

# Install some php extensions
RUN docker-php-ext-install \
    gd \
		mysql

# Avoid some php warnings
COPY ".docker/date.ini" "/usr/local/etc/php/conf.d/date.ini"

# Increase the PHP memory limit
COPY ".docker/memory-limit.ini" "/usr/local/etc/php/conf.d/memory-limit.ini"
