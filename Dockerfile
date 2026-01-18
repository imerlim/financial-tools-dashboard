FROM php:8.4-apache

# 2. System dependencies
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    curl \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql \
    && rm -rf /var/lib/apt/lists/*

# 3. Enable Apache rewrite and fix MPM conflict
RUN a2dismod mpm_event && a2enmod mpm_prefork rewrite

# 4. Set working directory
WORKDIR /var/www/html

# 5. Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 6. Copy ONLY composer files first
COPY composer.json composer.lock ./

# 7. Install PHP dependencies
RUN composer install \
    --no-dev \
    --no-interaction \
    --prefer-dist \
    --optimize-autoloader \
    --no-scripts

# 8. Copy the rest of the application
COPY . .

# 9. Laravel permissions
RUN chown -R www-data:www-data storage bootstrap/cache

# 10. Apache document root → /public
ENV APACHE_DOCUMENT_ROOT=/var/www/html/public
RUN sed -ri \
    -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' \
    /etc/apache2/sites-available/*.conf \
    /etc/apache2/apache2.conf
