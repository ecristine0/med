FROM php:8.2-apache

# Instala e habilita as extensões necessárias para conectar o PHP ao MySQL
RUN docker-php-ext-install mysqli pdo pdo_mysql && docker-php-ext-enable mysqli
