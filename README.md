Touche pas au Klaxon
Application de covoiturage interne — Architecture MVC en PHP
Touche pas au Klaxon est une application intranet permettant aux collaborateurs d’une entreprise de proposer des trajets inter-agences et de consulter ceux de leurs collègues. Ce projet a été réalisé en PHP 8, selon une architecture MVC maison, avec une mise en forme Bootstrap 5, des tests unitaires PHPUnit, et une vérification qualité du code via PHPStan.

 1. Architecture du projet
Le projet suit une organisation MVC claire :

public/
    index.php              → Point d’entrée unique (router)
    css/theme.css          → Palette personnalisable via variables Bootstrap

src/
    Core/
        Router.php         → Routeur minimaliste
        Database.php       → Connexion PDO (Singleton)

    Controller/
        HomeController.php
        AuthController.php
        TrajetController.php
        AdminController.php

    Model/
        Utilisateur.php
        Trajet.php
        Agence.php

    View/
        home.php
        login.php
        trajets/
            create.php
            edit.php
            show.php
        admin/
            index.php
            agences.php
        partials/
            header_app.php
            header_admin.php
            footer.php

tests/
    AgenceTest.php
    TrajetTest.php

phpstan.neon
phpunit.xml
composer.json


2. Design & thématisation
Le design utilise Bootstrap 5, mais la palette est entièrement personnalisable via le fichier :
➡️ public/css/theme.css

Palette imposée pour l'application :
#f1f8fc 
#0074c7 
#00497c 
#384050 
#cd2c2e 
#82b864 

Ces couleurs sont injectées dans les variables CSS Bootstrap :
:root {
    --bs-body-bg: #f1f8fc;
    --bs-body-color: #384050;
    --bs-primary: #0074c7;
    --bs-dark: #00497c;
    --bs-success: #82b864;
    --bs-danger: #cd2c2e;
}

4. Fonctionnalités

   
Visiteur (non connecté)

Accès à la liste des trajets à venir (places disponibles uniquement) 
Tri automatique par date de départ 
Bouton d’accès au formulaire de connexion 

Utilisateur connecté

Voir toutes les informations des trajets 

Fenêtre modale +d’infos avec :

- identité du conducteur 
- email 
- téléphone 
- nombre total de places

Proposer un trajet 

Modifier ses trajets 

Supprimer ses trajets 

Déconnexion 

Administrateur

Accès via un header dédié :
Tableau de bord (statistiques : utilisateurs, agences, trajets) 
Gestion des agences (CRUD) 
Menu de navigation admin horizontal 

5. Tests unitaires (PHPUnit)
   
Des tests unitaires couvrent les opérations d’écriture en base de données, comme exigé dans le cahier des charges.
Tests implémentés :
 tests/AgenceTest.php
création d’une agence 
vérification de l’existence en base 
suppression 
vérification de la suppression 
tests/TrajetTest.php
création d’un trajet complet 
vérification en base 
suppression (nettoyage) 
Commande d’exécution :
php composer.phar test

Qualité du code (PHPStan)
Analyse statique réalisée avec PHPStan, niveau 4.
Configuration : phpstan.neon
Lancer l’analyse :
php composer.phar analyse

6. Installation & configuration

Prérequis

PHP ≥ 8.0 
Composer 
MySQL / MariaDB 
MAMP ou équivalent 

Installation
git clone https://github.com/Yo9in/touche-pas-au-klaxon.git
cd touche-pas-au-klaxon
php composer.phar install

Base de données

$host = '127.0.0.1';
$port = 8889;   // port MySQL MAMP
$db   = 'covoiturage';
$user = 'root';
$pass = 'root';
Lancer l’application : 

php -S localhost:8000 -t public
Ouvrir : http://localhost:8000

Auteur
Développé par : Yoann MONLOUIS-FÉLICITÉ  
Année : 2025 
Projet : Mise en place d’une application MVC en PHP
