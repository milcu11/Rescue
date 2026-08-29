FROM php:8.4-cli

ENV APP_ENV=production \
    APP_DEBUG=false \
    PORT=8000

WORKDIR /app

RUN apt-get update \
    && apt-get install -y --no-install-recommends \
        curl \
        git \
        unzip \
        libicu-dev \
        libonig-dev \
        libpng-dev \
        libxml2-dev \
        libzip-dev \
        zlib1g-dev \
        libfreetype6-dev \
        libjpeg-dev \
        nodejs \
        npm \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip intl \
    && rm -rf /var/lib/apt/lists/*

COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

COPY composer.json composer.lock ./
RUN composer install --no-interaction --prefer-dist --no-progress --optimize-autoloader --no-dev --no-scripts

COPY package.json package-lock.json ./
RUN npm ci

COPY . .

RUN php artisan package:discover --ansi \
    && npm run build \
    && php artisan config:cache \
    && php artisan route:cache \
    && php artisan view:cache \
    && php artisan storage:link

EXPOSE 8000

CMD ["sh", "-c", "php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
