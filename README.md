# ElectroTech Store

Plateforme web e-commerce de matériel électronique, informatique et électroménager.

**Stagiaire :** ANDRIANARIVO Lalaina Bienvenu
**Stage :** BENJEDDOU Technologie Services (à distance)

## Technologies
- Back-end : PHP (structuré en couches, PDO)
- Base de données : MySQL
- Front-end : HTML, CSS, JavaScript

## Installation
1. Copier le dossier dans `htdocs` (XAMPP).
2. Démarrer Apache et MySQL depuis le panneau XAMPP.
3. Importer `database/electrotech_store.sql` dans phpMyAdmin.
4. Ouvrir http://localhost/electrotech_store/ dans le navigateur.

## Structure
- `config/`    : connexion à la base (database.php)
- `classes/`   : couche logique métier (une classe par entité)
- `includes/`  : éléments réutilisés (header, footer, auth)
- `admin/`     : interface administrateur
- `client/`    : interface client
- `assets/`    : css, js, images
- `uploads/`   : photos des produits
- `database/`  : script SQL de la base
