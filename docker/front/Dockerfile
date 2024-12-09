FROM php:8.3-apache

RUN a2enmod rewrite
COPY apache2.conf /etc/apache2/apache2.conf
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf
COPY php-extra.ini /usr/local/etc/php/conf.d
EXPOSE 80
