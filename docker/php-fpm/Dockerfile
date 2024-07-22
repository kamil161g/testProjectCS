FROM php:8.3-fpm

# Aktualizacja repozytoriów i instalacja zależności
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libpng-dev \
    libonig-dev \
    libxml2-dev


# Instalacja rozszerzeń PHP
RUN docker-php-ext-install pdo pdo_mysql mbstring exif pcntl bcmath gd

# Ustawienie katalogu roboczego
WORKDIR /var/www

# Instalacja Composera
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Ustawienie właściciela katalogu
RUN chown -R www-data:www-data /var/www

# Eksponowanie portu
EXPOSE 9000

# Uruchomienie crona w tle i start PHP-FPM
CMD php-fpm
