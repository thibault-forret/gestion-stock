FROM php:8.3-fpm

ARG user
ARG uid

# Install dependencies
RUN apt update && apt install -y \
    git \
    curl \
    libpng-dev \
    libonig-dev \
    unzip \
    zip \
    libxml2-dev

# Clean up to reduce image size
RUN apt clean && rm -rf /var/lib/apt/lists/*

# Install PHP extensions
RUN docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd

# Install Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Add user
RUN useradd -G www-data,root -u $uid -d /home/$user $user
RUN mkdir -p /home/$user/.composer && \
    chown -R $user:$user /home/$user

# Change ownership of the working directory to the created user
RUN chown -R $user:$user /var/www

# Set the working directory
WORKDIR /var/www

# Switch to the non-root user
USER $user
