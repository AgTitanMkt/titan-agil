FROM php:8.3-fpm

RUN chmod -R 777 storage bootstrap/cache

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

# copia apenas composer
COPY composer.json composer.lock ./

# instala deps sem scripts
RUN composer install \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-dev \
    --no-scripts

# copia resto do projeto
COPY . .

# roda scripts do laravel depois
RUN php artisan package:discover

CMD ["php-fpm"]
