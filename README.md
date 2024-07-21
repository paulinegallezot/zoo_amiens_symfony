# Zoo Arcadia

## Introduction
Bienvenue dans le dépôt GitHub de Zoo Arcadia, un projet Symfony 7.2 utilisant PHP 8.2. Ce projet comprend des intégrations avec Imagick pour la manipulation d'images, Doctrine pour ORM, ainsi qu'une utilisation conjointe de MariaDB et MongoDB Atlas.

## Configuration requise
- PHP 8.2
- Symfony 7.2
- Serveur MariaDB
- MongoDB Atlas
- Extension PHP Imagick

## Installation

### Cloner le dépôt
git clone !!

### Installation de Symfony CLI
Vous pouvez télécharger et installer Symfony CLI en suivant les instructions sur le site web de Symfony. Cela facilitera la gestion de votre serveur et de nombreuses autres tâches de développement.

### Installez toutes les dépendances nécessaires à l'aide de Composer
composer install

### Configuration des bases de données
symfony console make:migration 
symfony console doctrine:migrations:migrate
symfony console doctrine:fixtures:load

### Lance le projet et prie
symfony serve



### .ENV
Dans ton .en.local par exemple
DATABASE_URL=
MAILER_DSN=smtp://localhost:1025  # Mailhog
CORS_ALLOW_ORIGINS_API=^https://zoo-nuxt\.csiteasy\.com$,^http://localhost:3000$ #pour un peu securiser par exemple
EMAIL_FROM=
EMAIL_TO=
MONGODB_URI=
MONGODB_DB=

## ET AUSSI

Pour tester le system de mail Lance docker pour lancer  Mailhog




