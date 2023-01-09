FROM php:7.4-fpm-alpine

# Add composer
RUN curl -s https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer


