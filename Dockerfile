FROM php:8.1-apache

# Установка необходимых библиотек
RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

# Включение модуля rewrite для Apache
RUN a2enmod rewrite

# Создание директории для проекта
RUN mkdir -p /home/masud/www/

# Настройка прав для директории проекта
RUN chown -R www-data:www-data /home/masud/www/
RUN chmod -R 755 /home/masud/www/

# Установка рабочего каталога
WORKDIR /home/masud/www/

# Настройка DocumentRoot на директорию public
RUN sed -i 's|/var/www/html|/home/masud/www/src/public|g' /etc/apache2/sites-available/000-default.conf

# Настройка доступа к директории для Apache
RUN echo "<Directory /home/masud/www/> \n\
    Options Indexes FollowSymLinks \n\
    AllowOverride All \n\
    Require all granted \n\
</Directory>" >> /etc/apache2/apache2.conf

# Открытие порта 80
EXPOSE 80
