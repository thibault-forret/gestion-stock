FROM php:8.3-fpm

# Installation des dependances
RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    unzip \
    zip \
    libxml2-dev \
    # Réduit la taille de l'image
    && apt clean && rm -rf /var/lib/apt/lists/* \
    # Installation des extensions PHP
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd \
    # Installation Composer
    && curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer 

# Répertoire de travail
WORKDIR /var/www

# Changer le propriétaire et configurer Git
RUN chown -R www-data:www-data /var/www && \
    git config --global --add safe.directory /var/www

# Expose le port 9000 pour PHP-FPM
EXPOSE 9000
