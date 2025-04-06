FROM php:8.3-fpm

# Installazione delle dipendenze PHP
RUN apt-get update && apt-get install -y \
    build-essential \
    libpng-dev \
    libjpeg-dev \
    libonig-dev \
    libxml2-dev \
    zip \
    unzip \
    git \
    curl \
    libzip-dev \
    && docker-php-ext-install pdo_mysql mbstring exif pcntl bcmath gd zip

# Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Aggiunge un utente non-root
RUN useradd -m -s /bin/bash laraveluser

# Imposta il proprietario della directory di lavoro e dei file a laraveluser
WORKDIR /var/www
RUN chown -R laraveluser:laraveluser /var/www

# Passa a laraveluser
USER laraveluser

