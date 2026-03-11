FROM php:8.3-fpm

RUN apt-get update && apt-get install -y \
    git \
    curl \
    zip \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev

# Node
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# extensões php
RUN docker-php-ext-install \
    pdo_mysql \
    mbstring \
    exif \
    pcntl \
    bcmath \
    gd

# composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# otimização de cache
COPY composer.json composer.lock ./

RUN composer install \
    --no-dev \
    --optimize-autoloader \
    --no-interaction \
    --no-scripts

# agora copia o resto do projeto
COPY . .

# roda scripts do laravel
RUN php artisan package:discover

# build frontend
RUN npm install && npm run build

# permissões
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

CMD ["php-fpm"]
