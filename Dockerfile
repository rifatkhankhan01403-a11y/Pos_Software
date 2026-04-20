FROM php:8.2-fpm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    git curl zip unzip libpng-dev libonig-dev libxml2-dev libzip-dev

# Install PHP extensions Laravel commonly needs
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

COPY . .

# Install dependencies safely

RUN php artisan config:cache
RUN php artisan route:cache

CMD php artisan serve --host=0.0.0.0 --port=10000
