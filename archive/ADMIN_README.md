# Guide d'Administration - Forum Pokémon

## Installation

### 1. Mettre à jour la base de données

Exécutez le script SQL pour ajouter les fonctionnalités d'administration :

```bash
mysql -u root -p bdd_pksa < database_admin.sql
```

Ce script ajoute :
- Les colonnes `role` et `banned` à la table `users`
- Les colonnes `deleted_at` et `deleted_by` pour le soft delete sur `topics` et `posts`
- Un utilisateur admin par défaut

### 2. Compte administrateur par défaut

Un compte admin est créé automatiquement :
- **Email** : `admin@pokemon-forum.com`
- **Mot de passe** : `admin123`
- **Nom d'utilisateur** : `admin`

⚠️ **IMPORTANT** : Changez ce mot de passe immédiatement après la première connexion !

## Accès au backoffice

1. Connectez-vous avec un compte administrateur
2. Cliquez sur le bouton **"🔧 Administration"** dans le menu du forum
3. Ou accédez directement à : `http://myapi.localhost:8080/admin`

## Fonctionnalités

### 📊 Dashboard

Vue d'ensemble avec :
- Statistiques (utilisateurs, sujets, catégories, bannis)
- Derniers sujets créés
- Derniers utilisateurs inscrits

### 💬 Modération des sujets

- **Voir tous les sujets** (y compris supprimés)
- **Supprimer un sujet** (soft delete - peut être restauré)
- **Restaurer un sujet** supprimé

### 📝 Modération des messages

- **Voir tous les messages** récents
- **Supprimer un message** (soft delete)
- **Restaurer un message** supprimé

### 📁 Gestion des catégories

- **Voir toutes les catégories**
- **Modifier une catégorie** (nom et description)
- **Supprimer une catégorie** (suppression définitive - supprime aussi tous les sujets)

### 👥 Gestion des utilisateurs

- **Voir tous les utilisateurs**
- **Changer le rôle** d'un utilisateur (user/admin)
- **Bannir un utilisateur** :
  - Bannissement permanent (laisser la date vide)
  - Bannissement temporaire (choisir une date de fin)
  - Ajouter une raison (optionnel)
- **Débannir un utilisateur**

## Sécurité

- Seuls les utilisateurs avec le rôle `admin` peuvent accéder au backoffice
- Les utilisateurs bannis ne peuvent pas se connecter
- Un admin ne peut pas se bannir lui-même
- Un admin ne peut pas modifier son propre rôle

## Routes d'administration

- `/admin` - Dashboard
- `/admin/topics` - Liste des sujets
- `/admin/posts` - Liste des messages
- `/admin/categories` - Liste des catégories
- `/admin/users` - Liste des utilisateurs

## Notes importantes

1. **Soft Delete** : Les sujets et messages supprimés ne sont pas définitivement supprimés, ils sont marqués comme supprimés. Ils peuvent être restaurés.

2. **Bannissement** : Les utilisateurs bannis ne peuvent pas se connecter. Les bannissements temporaires expirent automatiquement à la date indiquée.

3. **Rôles** : 
   - `user` : Utilisateur normal
   - `admin` : Administrateur avec accès au backoffice

4. **Suppression de catégories** : Attention, supprimer une catégorie supprime également tous les sujets qu'elle contient (cascade).

