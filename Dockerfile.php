FROM php:8.2-apache

RUN docker-php-ext-install pdo pdo_mysql

WORKDIR /var/www/html

# Копируем публичные файлы (включая index.php) прямо в корень DocumentRoot,
# чтобы Apache видел их как основной сайт и не отдавал 403 Forbidden.
COPY public/ /var/www/html/

# Приложение (MVC-логика) лежит в подкаталоге app
COPY app /var/www/html/app

RUN a2enmod rewrite

EXPOSE 80

