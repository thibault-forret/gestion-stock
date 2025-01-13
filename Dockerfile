FROM php:8.3-fpm

# Installation des dependances
RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    unzip \
    zip \
    libxml2-dev

# Réduit la taille de l'image
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Installation des extensions PHP
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Installation Composer
RUN curl -sS https://getcomposer.org/installer | php -- --install-dir=/usr/bin --filename=composer

# Répertoire de travail
WORKDIR /var/www

# Changer le propriétaire du répertoire de travail
RUN chown -R www-data:www-data /var/www 

# Ajoute le répertoire de travail dans la configuration de git
RUN git config --global --add safe.directory /var/www 

# Expose le port 9000 pour PHP-FPM
EXPOSE 9000
