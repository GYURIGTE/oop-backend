FROM php:8.3-apache

# System libraries needed by the PHP extensions below.
RUN apt-get update && apt-get install -y --no-install-recommends \
        git \
        unzip \
        libpq-dev \
        libzip-dev \
        libonig-dev \
    && docker-php-ext-install pdo pdo_pgsql pdo_mysql zip mbstring opcache \
    && a2enmod rewrite \
    && rm -rf /var/lib/apt/lists/*

# Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Serve Laravel's public/ directory and allow .htaccess rewrites.
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri -e "s!/var/www/html!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/sites-available/*.conf \
    && sed -ri -e "s!/var/www/!${APACHE_DOCUMENT_ROOT}!g" /etc/apache2/apache2.conf /etc/apache2/conf-available/*.conf \
    && sed -ri -e "s/AllowOverride None/AllowOverride All/g" /etc/apache2/apache2.conf

WORKDIR /var/www/html

# Install PHP dependencies first so Docker can cache this layer.
COPY composer.json composer.lock ./
RUN composer install --no-dev --no-scripts --no-autoloader --prefer-dist --no-interaction

COPY . .

RUN composer dump-autoload --optimize --no-interaction \
    && (php artisan storage:link || true) \
    && chown -R www-data:www-data storage bootstrap/cache

COPY docker/start.sh /usr/local/bin/start.sh
RUN chmod +x /usr/local/bin/start.sh

EXPOSE 80

CMD ["start.sh"]
