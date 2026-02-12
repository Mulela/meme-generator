Générateur de Mèmes
Présentation

Ce projet est une mini application web permettant de créer facilement des mèmes à partir d’une image.

L’utilisateur peut :

importer une image (PNG ou JPG),

ajouter un texte en haut et/ou en bas,

visualiser le rendu en temps réel,

télécharger le mème généré,

l’enregistrer dans une galerie,

le partager,

ou le supprimer.

L’objectif était de réaliser une application simple mais propre, en respectant une logique claire côté backend et frontend.

Fonctionnalités principales

Téléchargement d’image depuis l’ordinateur

Aperçu en temps réel grâce au Canvas HTML5

Ajout de texte personnalisable (taille + contour)

Export en PNG

Enregistrement en base de données

Affichage dans une galerie paginée

Partage (Web Share API si disponible, sinon copie du lien)

Suppression d’un mème (base + fichier)

Choix techniques
Backend

Le projet utilise Laravel pour :

la gestion des routes

la validation des formulaires

la gestion des fichiers (storage)

l’accès à la base de données

la pagination

Frontend

Blade pour les vues

Tailwind CSS pour l’interface

Canvas HTML5 pour générer dynamiquement le mème côté client

Le rendu du mème se fait côté navigateur afin d’avoir un aperçu instantané sans solliciter inutilement le serveur.
Le serveur reçoit ensuite l’image finale en PNG pour l’enregistrement.

Base de données

SQLite est utilisé pour simplifier la configuration du projet en environnement de développement.

Installation
Prérequis

PHP 8+

Composer

Node.js + npm

Étapes

Installer les dépendances PHP :

composer install


Installer les dépendances frontend :

npm install


Copier le fichier d’environnement :

copy .env.example .env


Générer la clé d’application :

php artisan key:generate


Créer la base SQLite (si nécessaire) :

type nul > database\database.sqlite


Lancer les migrations :

php artisan migrate


Créer le lien vers le dossier storage :

php artisan storage:link


Lancer le serveur :

Terminal 1 :

php artisan serve


Terminal 2 :

npm run dev


Puis ouvrir :
http://localhost:8000

Structure générale

Les images sont stockées dans storage/app/public/memes

La base de données contient les informations suivantes :

chemin de l’image

texte du haut

texte du bas

date de création

Améliorations possibles

Gestion des utilisateurs

Bibliothèque de templates prédéfinis

Drag & drop du texte

Ajout d’emojis ou stickers

Optimisation des images

Remarques

Ce projet met l’accent sur :

la clarté de l’architecture

la séparation logique frontend / backend

la gestion correcte des erreurs

la cohérence fonctionnelle