FROM php:8.3-fpm

# Répertoire de travail
WORKDIR /var/www

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

# Copier tous les fichiers de l'application dans le conteneur
COPY . /var/www

# Changer le propriétaire et configurer Git
RUN chown -R www-data:www-data /var/www && \
    git config --global --add safe.directory /var/www

# Changer l'utilisateur par défaut de PHP-FPM
USER www-data

# Exécuter composer install pour installer les dépendances PHP
RUN composer install

# Expose le port 9000 pour PHP-FPM
EXPOSE 9000

# Lancer le serveur PHP-FPM
CMD ["php-fpm"]
