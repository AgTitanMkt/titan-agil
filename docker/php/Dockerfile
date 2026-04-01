FROM php:8.3-fpm
# dependencias do sistema
RUN apt-get update && apt-get install -y \
    git curl zip unzip \
    libpng-dev libonig-dev libxml2-dev
# Node.js
RUN curl -fsSL https://deb.nodesource.com/setup_20.x | bash - \
    && apt-get install -y nodejs
# extensoes PHP
RUN docker-php-ext-install \
    pdo_mysql mbstring exif pcntl bcmath gd
# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer
WORKDIR /var/www
# copia tudo
COPY . .
# configuracao PHP-FPM
COPY docker/php/php-fpm.conf /usr/local/etc/php-fpm.d/www.conf
# instala backend
RUN composer install --no-dev --optimize-autoloader
# build frontend
RUN npm install && npm run build
# permissoes
RUN chown -R www-data:www-data /var/www \
    && chmod -R 775 storage bootstrap/cache
CMD ["php-fpm"]