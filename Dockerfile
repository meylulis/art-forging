FROM php:8.2-apache

# Установим нужные зависимости
RUN apt-get update && apt-get install -y \
    libzip-dev zip unzip git libonig-dev libpng-dev libjpeg-dev libfreetype6-dev mariadb-client \
    && docker-php-ext-install pdo pdo_mysql zip

# Apache модуль rewrite
RUN a2enmod rewrite

# Копируем конфигурацию Apache
COPY apache.conf /etc/apache2/sites-available/000-default.conf

# Копируем проект
COPY . /var/www/html/

# Работаем из директории проекта
WORKDIR /var/www/html

# Установка зависимостей
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer
RUN composer install --no-interaction

# Разрешения
RUN chown -R www-data:www-data /var/www/html && chmod -R 755 /var/www/html
