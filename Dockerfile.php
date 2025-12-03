FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

# Докер-образ содержит базовую структуру, но в режиме разработки
# основной код будет подмонтирован томами из docker-compose.
COPY public ./public
COPY app ./app

# Меняем DocumentRoot Apache на public, чтобы index.php и статика
# брались из /var/www/html/public.
RUN sed -i 's|DocumentRoot /var/www/html|DocumentRoot /var/www/html/public|g' /etc/apache2/sites-available/000-default.conf

RUN a2enmod rewrite

EXPOSE 80

