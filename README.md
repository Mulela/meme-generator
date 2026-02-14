🎭 Générateur de Mèmes – Mini Application Web
📌 Description

Cette application web permet de créer des mèmes personnalisés à partir d’une image importée par l’utilisateur.

- L’utilisateur peut :

Ajouter un texte en haut et en bas de l’image

Ajuster la taille du texte

Activer ou désactiver le contour

Télécharger le mème en format PNG

Enregistrer le mème dans une galerie

Supprimer ou partager un mème enregistré

Le rendu est généré dynamiquement côté client grâce à l’API Canvas de HTML5.

- L’objectif de ce projet était de concevoir une mini-application complète, incluant :

Interface utilisateur

Traitement graphique côté front-end

Enregistrement en base de données

Stockage des fichiers

Interaction AJAX avec le backend
-----------------------------------
🛠 Stack Technique
- Backend

Laravel 12

PHP 8.4

SQLite (environnement de développement)

- Frontend

Blade (templating Laravel)

Tailwind CSS 4

JavaScript natif

HTML5 Canvas API

- Outils

Vite

Git (versioning)

SQLite pour la simplicité en développement
----------------------------------------------
⚙ Installation
1️⃣ Cloner le projet
git clone <url-du-repo>
cd meme-generator

2️⃣ Installer les dépendances
composer install
npm install

3️⃣ Configuration

Copier le fichier d’environnement :

cp .env.example .env


Générer la clé :

php artisan key:generate

4️⃣ Base de données (SQLite)

Créer le fichier :

touch database/database.sqlite


Puis lancer les migrations :

php artisan migrate

5️⃣ Lancer le projet

Terminal 1 :

php artisan serve


Terminal 2 :

npm run dev


Application accessible sur :

http://localhost:8000


--------------------------------

🚀 Fonctionnalités
🎨 Création de mème

Import d’image (PNG / JPG)

Drag & drop possible

Aperçu en temps réel

Ajustement dynamique de la taille du texte

Activation/désactivation du contour

Gestion automatique du retour à la ligne

💾 Export

Téléchargement du mème en PNG

📂 Galerie

Enregistrement en base de données

Stockage des images via le disque public Laravel

Pagination

Suppression d’un mème

Partage via lien direct

--------------------------------------------------------


🧠 Notes Techniques
🔹 Redimensionnement intelligent du canvas

Les images trop grandes sont redimensionnées proportionnellement afin d’éviter des problèmes d’alignement et de performance.

🔹 Canvas API

Le texte est dessiné via :

ctx.fillText()

ctx.strokeText() pour le contour

measureText() pour gérer automatiquement les retours à la ligne

🔹 Gestion AJAX

L’enregistrement en galerie se fait via fetch() avec :

Token CSRF

Réponse JSON

Gestion d’erreurs

Feedback utilisateur

🔹 Sécurité

Validation côté frontend (type et taille image)

Validation côté backend (Laravel validation rules)

Protection CSRF

Nettoyage en cas d’erreur serveur

🔹 Architecture

Séparation claire Frontend / Backend

Stockage des images dans storage/app/public

Base SQLite en développement

Code documenté pour la maintenabilité

-----------------------------------------------

📌 Perspectives d’amélioration

Authentification utilisateur

Galerie personnelle par utilisateur

Templates prédéfinis

Déploiement cloud

Compression d’image automatique

-------------------------------------------------

Projet réalisé dans le cadre d’un test technique d’admission.
