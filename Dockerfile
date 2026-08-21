# ==========================================
# Multi-Stage Build: 1. Frontend Asset Builder
# ==========================================
FROM node:20-alpine AS frontend
WORKDIR /app
COPY package*.json vite.config.js ./
RUN npm install
COPY resources resources
COPY public public
RUN npm run build

# ==========================================
# Multi-Stage Build: 2. PHP Production Image
# ==========================================
FROM php:8.2-cli-alpine

# Install dependencies sistem & ekstensi PHP yang dibutuhkan Laravel
RUN apk add --no-cache \
    bash \
    curl \
    git \
    libpng-dev \
    libjpeg-turbo-dev \
    freetype-dev \
    libzip-dev \
    icu-dev \
    oniguruma-dev \
    sqlite-dev \
    postgresql-dev \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install -j$(nproc) \
        pdo \
        pdo_mysql \
        pdo_sqlite \
        pdo_pgsql \
        mbstring \
        zip \
        gd \
        bcmath \
        intl \
        opcache \
        pcntl

# Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Set working directory
WORKDIR /var/www/html

# Copy seluruh source code proyek
COPY . .

# Copy build assets dari stage 1 (Node frontend)
COPY --from=frontend /app/public/build ./public/build

# Install dependensi PHP via composer
RUN composer install --no-dev --optimize-autoloader --no-interaction --prefer-dist

# Beri permission ke storage dan bootstrap cache
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Port default Render adalah 10000
EXPOSE 10000

# Script start-up container
CMD sh -c "\
    mkdir -p database && touch database/database.sqlite && \
    php artisan storage:link --force || true && \
    php artisan migrate --force || true && \
    php artisan config:cache && \
    php artisan route:cache && \
    php artisan view:cache && \
    php artisan serve --host=0.0.0.0 --port=\${PORT:-10000}"
