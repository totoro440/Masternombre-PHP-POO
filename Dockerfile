FROM php:8.2-apache

# Installation des dépendances système nécessaires (zip pour Composer)
RUN apt-get update && apt-get install -y \
    git \
    unzip \
    libzip-dev \
    && docker-php-ext-install zip

# Modification du DocumentRoot d'Apache pour pointer vers le dossier /public (Sécurité Pro)
ENV APACHE_DOCUMENT_ROOT /var/www/html/public
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/sites-available/*.conf
RUN sed -ri -e 's!/var/www/html!${APACHE_DOCUMENT_ROOT}!g' /etc/apache2/apache2.conf

# Installation de Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

WORKDIR /var/www/html

# --- LES LIGNES POUR LA BASE DE DONNÉES SQLITE ---
# Création du dossier et du fichier de base de données
RUN mkdir -p /var/www/html/database \
    && touch /var/www/html/database/database.sqlite

EXPOSE 80