# Mon Projet Symfony

Ce projet Symfony est un kanban (ajoutez une brève description de votre projet ici).

## Prérequis

Avant de commencer, assurez-vous d'avoir les éléments suivants installés sur votre système :

- PHP
- Composer
- Symfony CLI

## Installation

1. Clonez ce dépôt sur votre machine :

   ```bash
   git clone https://github.com/Molky-dev/fukbanban
   ```

2. Lancer le serveur :

   ```bash
   symfony server:start
   ```
   
3. Créer la base de données :

   ```bash
   php bin/console make:migration 
    ```
   
4. Créer les tables :

    ```bash
     php bin/console doctrine:migrations:migrate
     ```
   
5. Charger les données de test :

    ```bash
    php bin/console doctrine:fixtures:load
   ```
   
6. Ouvrez votre navigateur et rendez-vous à l'adresse `localhost:8000` (ou autre, selon la configuration de votre machine).

    ```bash
    symfony open:local
    ```

