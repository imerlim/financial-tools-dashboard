# Usamos a imagem oficial do PHP 8.2 com Apache
FROM php:8.2-apache

# Instalamos extensões necessárias para o Laravel e S3
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \
    libfreetype6-dev \
    zip \
    unzip \
    git \
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install gd pdo pdo_mysql

# Habilitamos o mod_rewrite do Apache (essencial para rotas do Laravel)
RUN a2enmod rewrite

# Definimos o diretório de trabalho
WORKDIR /var/www/html

# Copiamos os arquivos do projeto para o container
COPY . .

# Instalamos o Composer dentro do container
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/local/bin --filename=composer
RUN composer install --no-dev --optimize-autoloader

# Ajustamos permissões para o Laravel
RUN chown -R www-data:www-data storage bootstrap/cache

# Apontamos o Apache para a pasta /public do Laravel
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf