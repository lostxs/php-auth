FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libpq-dev \
    && docker-php-ext-install pdo_pgsql

RUN a2enmod rewrite

RUN mkdir -p /home/masud/www/

RUN chown -R www-data:www-data /home/masud/www/
RUN chmod -R 755 /home/masud/www/

WORKDIR /home/masud/www/

RUN sed -i 's|/var/www/html|/home/masud/www/src/public|g' /etc/apache2/sites-available/000-default.conf

RUN echo "<Directory /home/masud/www/> \n\
    Options Indexes FollowSymLinks \n\
    AllowOverride All \n\
    Require all granted \n\
</Directory>" >> /etc/apache2/apache2.conf

EXPOSE 80
