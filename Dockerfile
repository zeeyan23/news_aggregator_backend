FROM php:8.2-apache
RUN docker-php-ext-install pdo pdo_mysql mysqli
RUN a2enmod rewrite
WORKDIR /var/www/html
COPY . /var/www/html
RUN chown -R www-data:www-data /var/www/html
RUN chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
# Install Supervisor
RUN apt-get update && apt-get install -y supervisor && apt-get clean

# Add Supervisor configuration file
COPY supervisord.conf /etc/supervisor/conf.d/supervisord.conf
RUN composer install --no-dev --optimize-autoloader
EXPOSE 80
CMD ["apache2-foreground"]
