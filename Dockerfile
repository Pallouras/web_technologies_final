FROM php:8.2-apache

# Εγκατάσταση πακέτων & Composer
RUN apt-get update && apt-get install -y \
    curl \
    unzip \
    git \
    default-mysql-client \
    libyaml-dev \
    && pecl install yaml && docker-php-ext-enable yaml \
    && docker-php-ext-install mysqli pdo_mysql \
    && curl -sS https://getcomposer.org/installer | php \
    && mv composer.phar /usr/local/bin/composer \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# 🔧 Αυξάνουμε το timeout του Composer
RUN composer config --global process-timeout 900

# Αντιγραφή του entrypoint script + init-db.sh
COPY scripts/entrypoint.sh /entrypoint.sh
COPY scripts/init-db.sh /scripts/init-db.sh
RUN chmod +x /entrypoint.sh /scripts/init-db.sh

# Αντιγραφή των αρχείων της εφαρμογής
COPY . /var/www/html/

# Τελική ρύθμιση
RUN chown -R www-data:www-data /var/www/html
ENTRYPOINT ["/entrypoint.sh"]