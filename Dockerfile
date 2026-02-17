# Stage 1: Frontend Build (Vite + React)
FROM node:22-alpine AS node-builder
WORKDIR /app
COPY package*.json ./
RUN npm install
COPY . .
RUN npm run build

# Stage 2: PHP Application (Production)
FROM php:8.4-fpm-alpine
WORKDIR /var/www

# Install system dependencies and PHP extensions for Laravel 12
RUN apk add --no-cache \
    git curl libpng-dev libxml2-dev zip unzip libzip-dev \
    oniguruma-dev icu-dev supervisor
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Copy application code
COPY . .
# Copy compiled assets from Stage 1
COPY --from=node-builder /app/public/build ./public/build

# Install production dependencies
RUN composer install --no-dev --optimize-autoloader

# Set permissions for Laravel
RUN chown -R www-data:www-data /var/www/storage /var/www/bootstrap/cache \
    && chmod -R 775 /var/www/storage /var/www/bootstrap/cache

EXPOSE 9000
CMD ["php-fpm"]