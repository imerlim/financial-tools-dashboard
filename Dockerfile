# 1. Use PHP 8.4 (matches composer.lock)
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
RUN a2dismod mpm_event || true && a2enmod mpm_prefork
RUN a2enmod rewrite
# Adicione esta linha para garantir que o Apache não tente carregar o event de novo
RUN echo "LoadModule mpm_prefork_module /usr/lib/apache2/modules/mod_mpm_prefork.so" > /etc/apache2/mods-available/mpm_prefork.load

# 4. Set working directory
WORKDIR /var/www/html

# 5. Install Composer
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# 6. Copy ONLY composer files first (better caching)
COPY composer.json composer.lock ./

# 7. Install PHP dependencies (added --no-scripts)
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
