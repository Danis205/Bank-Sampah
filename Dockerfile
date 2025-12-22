# Use the official PHP image with Apache
FROM php:8.2-apache

# 1. Install development packages
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libpq-dev \
    libzip-dev \
 && docker-php-ext-install pdo_mysql pdo_pgsql mbstring exif pcntl bcmath gd zip

# 2. Enable Apache mod_rewrite
RUN a2enmod rewrite

# 3. Set the working directory
WORKDIR /var/www/html

# 4. Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# 5. Copy application source
COPY . .

# 6. Install PHP dependencies
RUN composer install --no-interaction --optimize-autoloader --no-dev

# 7. Set permissions
RUN chown -R www-data:www-data /var/www/html/storage /var/www/html/bootstrap/cache

# 8. Configure Apache DocumentRoot
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# 9. Expose port 80
EXPOSE 80

# 10. Start using the entrypoint script
COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

CMD ["/usr/local/bin/entrypoint.sh"]