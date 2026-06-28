FROM php:8.4-fpm-alpine

# Install system deps
RUN apk add --no-cache nginx supervisor curl unzip git nodejs npm \
    libpng-dev libjpeg-turbo-dev freetype-dev \
    postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_pgsql gd opcache

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

COPY . .

# Install PHP deps
RUN composer update league/flysystem-aws-s3-v3 --no-dev --no-interaction
RUN composer install --no-dev --optimize-autoloader

# Install JS deps and build assets
RUN npm ci && npm run build

# Permissions
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache

# Nginx config
COPY docker/nginx.conf /etc/nginx/nginx.conf

# Supervisor config (runs nginx + php-fpm together)
COPY docker/supervisord.conf /etc/supervisord.conf

EXPOSE 10000

CMD ["/usr/bin/supervisord", "-c", "/etc/supervisord.conf"]
