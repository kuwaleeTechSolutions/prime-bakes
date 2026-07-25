FROM php:8.3-apache

WORKDIR /var/www/html

RUN apt-get update && apt-get install -y \
    git \
    unzip \
    curl \
    nodejs \
    npm \
    libzip-dev

RUN docker-php-ext-install pdo pdo_mysql zip

RUN a2enmod rewrite

COPY . .

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

RUN composer install --no-dev --optimize-autoloader

RUN npm install

RUN npm run build

RUN php artisan storage:link || true

RUN chown -R www-data:www-data storage bootstrap/cache

EXPOSE 80

CMD ["apache2-foreground"]

RUN a2enmod rewrite

COPY apache.conf /etc/apache2/sites-available/000-default.conf