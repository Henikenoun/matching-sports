# Étape 1 :  L'image officielle php:8.2-apache inclut PHP avec le serveur Apache déjà configuré.
FROM php:8.2-apache

# Étape 2 : Installer les dépendances nécessaires pour Laravel
RUN apt-get update && apt-get install -y \
    libpng-dev \
    libjpeg-dev \ 
    # nécessaires pour Laravel (par exemple, pour manipuler des images, gérer des fichiers ZIP, etc.).|
    libfreetype6-dev \
    libonig-dev \
    libzip-dev \
    unzip \
    git \
    curl \
    nano \
    #Indispensable pour MySQL, votre base de données.
    && docker-php-ext-configure gd --with-freetype --with-jpeg \
    && docker-php-ext-install pdo pdo_mysql mbstring gd zip opcache

# Étape 3 : Activer le module Apache mod_rewrite pour Laravel
#Laravel nécessite le module mod_rewrite pour la gestion des routes
RUN a2enmod rewrite

# Étape 4 : Installer Composer pour gérer les dépendances Laravel
COPY --from=composer:2 /usr/bin/composer /usr/bin/composer

# Étape 5 : Définir le répertoire de travail
#: Définit où les fichiers de votre application seront placés dans le conteneur.
WORKDIR /var/www/html

# Étape 6 : Copier les fichiers de l'application dans le conteneur
COPY . .

# Étape 7 : Copier le fichier .env dans le conteneur
# Copie votre fichier .env spécifique à votre configuration dans le conteneur.
COPY .env /var/www/html/.env

# Étape 8 : Installer les dépendances Laravel
#L'option --no-dev exclut les dépendances de développement en production.
RUN composer install --no-dev --optimize-autoloader

# Étape 9 : Configurer les permissions pour Laravel
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 775 /var/www/html/storage /var/www/html/bootstrap/cache

# Étape 10 : Configurer les tâches Cron pour Laravel
RUN echo "* * * * * www-data php /var/www/html/artisan schedule:run >> /dev/null 2>&1" >> /etc/cron.d/laravel-scheduler

# Étape 11 : Exposer le port 80 pour Apache
EXPOSE 80

# Étape 12 : Lancer Apache en mode foreground
#Démarre Apache en mode "foreground" pour que le conteneur reste actif.
CMD ["apache2-foreground"]
