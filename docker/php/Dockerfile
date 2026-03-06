FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev

RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# copia composer primeiro (cache do docker)
COPY composer.json composer.lock ./

RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-dev \
    --no-scripts

# copia projeto
COPY . .

# permissões laravel
RUN chmod -R 777 storage bootstrap/cache

# scripts laravel
RUN php artisan package:discover --ansi || true

CMD ["php-fpm"]
