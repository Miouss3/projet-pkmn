# Forum Pokémon - Application MVC

Un forum moderne sur les Pokémon développé en PHP avec une architecture MVC (Modèle-Vue-Contrôleur).

## Fonctionnalités

- ✅ **Authentification** : Inscription et connexion des utilisateurs
- ✅ **Catégories** : Création et gestion de catégories de discussion
- ✅ **Sujets** : Création de sujets dans les catégories
- ✅ **Messages** : Postage de messages dans les sujets
- ✅ **URLs propres** : Système de routage avec URLs lisibles
- ✅ **Interface moderne** : Design responsive et moderne

## Structure du projet

```
myapi/
├── app/
│   ├── controllers/      # Contrôleurs MVC
│   │   ├── AuthController.php
│   │   ├── CategoryController.php
│   │   ├── HomeController.php
│   │   ├── PostController.php
│   │   ├── TopicController.php
│   │   └── BaseController.php
│   ├── models/           # Modèles de données
│   │   ├── User.php
│   │   ├── Category.php
│   │   ├── Topic.php
│   │   └── Post.php
│   ├── views/            # Vues (templates)
│   │   ├── layout/
│   │   ├── auth/
│   │   ├── category/
│   │   ├── topic/
│   │   └── home/
│   ├── Database.php      # Connexion à la base de données
│   └── Router.php        # Système de routage
├── config/
│   └── config.php        # Configuration de la base de données
├── index.php             # Point d'entrée de l'application
├── .htaccess            # Configuration Apache pour URLs propres
├── database.sql         # Script de création de la base de données
└── composer.json        # Configuration Composer

```

## Installation

### 1. Prérequis

- PHP 7.4 ou supérieur
- MySQL/MariaDB
- Apache avec mod_rewrite activé
- Composer

### 2. Configuration de la base de données

1. Modifiez le fichier `config/config.php` avec vos paramètres de base de données :
```php
return [
    'host'     => 'localhost',
    'dbname'   => 'bdd_pksa',
    'username' => 'votre_utilisateur',
    'password' => 'votre_mot_de_passe',
    'charset'  => 'utf8mb4'
];
```

2. Importez le script SQL :
```bash
mysql -u root -p < database.sql
```

### 3. Installation des dépendances

```bash
composer install
```

### 4. Configuration Apache

Assurez-vous que le module `mod_rewrite` est activé et que votre configuration Apache permet les fichiers `.htaccess`.

## Utilisation

### Routes disponibles

- `/` - Page d'accueil (liste des catégories)
- `/login` - Connexion
- `/register` - Inscription
- `/logout` - Déconnexion
- `/categories/{id}` - Voir une catégorie et ses sujets
- `/categories/create` - Créer une catégorie (nécessite connexion)
- `/topics/{id}` - Voir un sujet et ses messages
- `/topics/create` - Créer un sujet (nécessite connexion)
- `/topics/create/{categoryId}` - Créer un sujet dans une catégorie spécifique

### Exemples d'URLs

- `http://localhost/categories/1` - Voir la catégorie #1
- `http://localhost/topics/5` - Voir le sujet #5
- `http://localhost/topics/create/2` - Créer un sujet dans la catégorie #2

## Architecture MVC

### Modèles (Models)
Les modèles gèrent l'accès aux données :
- `User` : Gestion des utilisateurs
- `Category` : Gestion des catégories
- `Topic` : Gestion des sujets
- `Post` : Gestion des messages

### Vues (Views)
Les vues affichent les données :
- Layout réutilisable avec header et footer
- Vues spécifiques pour chaque fonctionnalité

### Contrôleurs (Controllers)
Les contrôleurs gèrent la logique métier :
- `HomeController` : Page d'accueil
- `AuthController` : Authentification
- `CategoryController` : Gestion des catégories
- `TopicController` : Gestion des sujets
- `PostController` : Gestion des messages

## Sécurité

- Mots de passe hashés avec `password_hash()`
- Protection contre les injections SQL avec PDO et requêtes préparées
- Échappement des données avec `htmlspecialchars()`
- Vérification de session pour les actions nécessitant une connexion

## Développement

Pour activer l'affichage des erreurs en développement, le fichier `index.php` contient :
```php
ini_set('display_errors', '1');
error_reporting(E_ALL);
```

**Important** : Désactivez ces options en production !

## Licence

Ce projet est un exemple éducatif.

