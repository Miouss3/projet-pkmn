<?php

namespace App\Models;

use App\Database;
use PDO;

/**
 * User - Modèle pour gérer les utilisateurs
 * 
 * Contient toutes les méthodes pour interagir avec la table users
 */
class User
{
    private $pdo;

    /**
     * Constructeur - Initialise la connexion à la base de données
     */
    public function __construct()
    {
        // Récupère l'instance unique de Database
        $database = Database::getInstance();
        
        // Récupère la connexion PDO
        $this->pdo = $database->getConnection();
    }

    /**
     * Trouve un utilisateur par son email
     * 
     * @param string $email L'email de l'utilisateur
     * @return array|null Les données de l'utilisateur ou null si pas trouvé
     */
    public function findByEmail(string $email): ?array
    {
        // Prépare la requête SQL
        $sql = 'SELECT * FROM users WHERE email = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête avec l'email
        $req->execute([$email]);
        
        // Récupère le résultat
        $user = $req->fetch(PDO::FETCH_ASSOC);
        
        // Si un utilisateur a été trouvé, on le retourne
        if ($user) {
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Trouve un utilisateur par son ID
     * 
     * @param int $id L'ID de l'utilisateur
     * @return array|null Les données de l'utilisateur ou null si pas trouvé
     */
    public function findById(int $id): ?array
    {
        // Prépare la requête SQL
        $sql = 'SELECT * FROM users WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête avec l'ID
        $req->execute([$id]);
        
        // Récupère le résultat
        $user = $req->fetch(PDO::FETCH_ASSOC);
        
        // Si un utilisateur a été trouvé, on le retourne
        if ($user) {
            return $user;
        } else {
            return null;
        }
    }

    /**
     * Crée un nouvel utilisateur
     * 
     * @param array $data Les données de l'utilisateur (username, email, password)
     * @return int L'ID du nouvel utilisateur créé
     */
    public function create(array $data): int
    {
        // Prépare la requête SQL d'insertion
        $sql = 'INSERT INTO users (username, email, password, created_at) 
                VALUES (?, ?, ?, NOW())';
        $req = $this->pdo->prepare($sql);
        
        // Hash le mot de passe pour la sécurité
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Exécute la requête avec les données
        $req->execute([
            $data['username'],
            $data['email'],
            $hashedPassword
        ]);
        
        // Récupère l'ID du nouvel utilisateur créé
        $newUserId = $this->pdo->lastInsertId();
        
        // Convertit en entier et retourne
        return (int) $newUserId;
    }

    /**
     * Vérifie si un mot de passe correspond au hash stocké
     * 
     * @param string $password Le mot de passe en clair
     * @param string $hash Le hash stocké en base de données
     * @return bool true si le mot de passe est correct, false sinon
     */
    public function verifyPassword(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    /**
     * Récupère tous les utilisateurs
     * 
     * @return array Liste de tous les utilisateurs
     */
    public function getAll(): array
    {
        // Prépare la requête SQL
        $sql = 'SELECT id, username, email, role, banned, banned_until, ban_reason, created_at 
                FROM users 
                ORDER BY created_at DESC';
        $req = $this->pdo->query($sql);
        
        // Récupère tous les résultats
        $users = $req->fetchAll(PDO::FETCH_ASSOC);
        
        return $users;
    }

    /**
     * Vérifie si un utilisateur est administrateur
     * 
     * @param int $userId L'ID de l'utilisateur
     * @return bool true si admin, false sinon
     */
    public function isAdmin(int $userId): bool
    {
        // Récupère l'utilisateur
        $user = $this->findById($userId);
        
        // Si l'utilisateur n'existe pas, il n'est pas admin
        if (!$user) {
            return false;
        }
        
        // Vérifie si le rôle est 'admin'
        if ($user['role'] === 'admin') {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Vérifie si un utilisateur est banni
     * 
     * @param int $userId L'ID de l'utilisateur
     * @return bool true si banni, false sinon
     */
    public function isBanned(int $userId): bool
    {
        // Récupère l'utilisateur
        $user = $this->findById($userId);
        
        // Si l'utilisateur n'existe pas ou n'est pas marqué comme banni
        if (!$user || !$user['banned']) {
            return false;
        }
        
        // Vérifie si le ban est permanent (banned_until est null)
        if ($user['banned_until'] === null) {
            return true; // Ban permanent
        }
        
        // Vérifie si le ban temporaire est toujours actif
        $banUntilTimestamp = strtotime($user['banned_until']);
        $currentTimestamp = time();
        
        if ($banUntilTimestamp > $currentTimestamp) {
            return true; // Ban toujours actif
        } else {
            return false; // Ban expiré
        }
    }

    /**
     * Bannit un utilisateur
     * 
     * @param int $userId L'ID de l'utilisateur
     * @param string|null $reason La raison du bannissement (optionnel)
     * @param string|null $until Date jusqu'à laquelle bannir (optionnel, null = permanent)
     * @return bool true si succès, false sinon
     */
    public function ban(int $userId, ?string $reason = null, ?string $until = null): bool
    {
        // Prépare la requête SQL
        $sql = 'UPDATE users SET banned = 1, ban_reason = ?, banned_until = ? WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([$reason, $until, $userId]);
        
        return $success;
    }

    /**
     * Débannit un utilisateur
     * 
     * @param int $userId L'ID de l'utilisateur
     * @return bool true si succès, false sinon
     */
    public function unban(int $userId): bool
    {
        // Prépare la requête SQL
        $sql = 'UPDATE users SET banned = 0, ban_reason = NULL, banned_until = NULL WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([$userId]);
        
        return $success;
    }

    /**
     * Met à jour le rôle d'un utilisateur
     * 
     * @param int $userId L'ID de l'utilisateur
     * @param string $role Le nouveau rôle ('user' ou 'admin')
     * @return bool true si succès, false sinon
     */
    public function updateRole(int $userId, string $role): bool
    {
        // Prépare la requête SQL
        $sql = 'UPDATE users SET role = ? WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([$role, $userId]);
        
        return $success;
    }
}
