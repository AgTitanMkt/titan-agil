FROM php:8.3-fpm

# dependências do sistema
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev

# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs

# extensões PHP
RUN docker-php-ext-install \
    pdo_mysql mbstring exif pcntl bcmath gd

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www

# copia tudo
COPY . .

# instala backend
RUN composer install --no-dev --optimize-autoloader

# build frontend (Vite 🔥)
RUN npm install && npm run build

# permissões
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache

CMD ["php-fpm"]