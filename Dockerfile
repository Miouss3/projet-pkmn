FROM php:8.4-apache

# Installer les dépendances système + client MySQL pour mysqldump
RUN apt-get update && \
    apt-get install -y libicu-dev default-mysql-client && \
    docker-php-ext-install pdo_mysql mysqli intl && \
    apt-get clean && rm -rf /var/lib/apt/lists/*

RUN a2enmod rewrite

WORKDIR /var/www/html

EXPOSE 8080

CMD ["apache2-foreground"]
