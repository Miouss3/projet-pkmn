<?php

namespace App\Models;

use App\Database;
use PDO;

/**
 * Post - Modèle pour gérer les messages (posts)
 * 
 * Contient toutes les méthodes pour interagir avec la table posts
 */
class Post
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
     * Récupère tous les messages d'un sujet
     * 
     * @param int $topicId L'ID du sujet
     * @return array Liste des messages du sujet
     */
    public function findByTopic(int $topicId): array
    {
        // Prépare la requête SQL avec jointure pour récupérer le nom de l'auteur
        $sql = 'SELECT p.*, u.username as author_name
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.topic_id = ? AND p.deleted_at IS NULL
                ORDER BY p.created_at ASC';
        
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête avec l'ID du sujet
        $req->execute([$topicId]);
        
        // Récupère tous les résultats
        $posts = $req->fetchAll(PDO::FETCH_ASSOC);
        
        return $posts;
    }

    /**
     * Trouve un message par son ID
     * 
     * @param int $id L'ID du message
     * @return array|null Les données du message ou null si pas trouvé
     */
    public function findById(int $id): ?array
    {
        // Prépare la requête SQL avec jointure pour récupérer le nom de l'auteur
        $sql = 'SELECT p.*, u.username as author_name
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                WHERE p.id = ?';
        
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête avec l'ID
        $req->execute([$id]);
        
        // Récupère le résultat
        $post = $req->fetch(PDO::FETCH_ASSOC);
        
        // Si un message a été trouvé, on le retourne
        if ($post) {
            return $post;
        } else {
            return null;
        }
    }

    /**
     * Crée un nouveau message
     * 
     * @param array $data Les données du message (content, topic_id, user_id)
     * @return int L'ID du nouveau message créé
     */
    public function create(array $data): int
    {
        // Prépare la requête SQL d'insertion
        $sql = 'INSERT INTO posts (content, topic_id, user_id, created_at) 
                VALUES (?, ?, ?, NOW())';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête avec les données
        $req->execute([
            $data['content'],
            $data['topic_id'],
            $data['user_id']
        ]);
        
        // Récupère l'ID du nouveau message créé
        $newPostId = $this->pdo->lastInsertId();
        
        // Convertit en entier et retourne
        return (int) $newPostId;
    }

    /**
     * Met à jour un message
     * 
     * @param int $id L'ID du message
     * @param array $data Les nouvelles données (content)
     * @return bool true si succès, false sinon
     */
    public function update(int $id, array $data): bool
    {
        // Prépare la requête SQL
        $sql = 'UPDATE posts SET content = ? WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([
            $data['content'],
            $id
        ]);
        
        return $success;
    }

    /**
     * Supprime définitivement un message
     * 
     * @param int $id L'ID du message
     * @return bool true si succès, false sinon
     */
    public function delete(int $id): bool
    {
        // Prépare la requête SQL
        $sql = 'DELETE FROM posts WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([$id]);
        
        return $success;
    }

    /**
     * Supprime "en douceur" un message (soft delete)
     * Le message n'est pas vraiment supprimé, juste marqué comme supprimé
     * 
     * @param int $id L'ID du message
     * @param int $deletedBy L'ID de l'utilisateur qui supprime
     * @return bool true si succès, false sinon
     */
    public function softDelete(int $id, int $deletedBy): bool
    {
        // Prépare la requête SQL
        $sql = 'UPDATE posts SET deleted_at = NOW(), deleted_by = ? WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([$deletedBy, $id]);
        
        return $success;
    }

    /**
     * Restaure un message supprimé (annule le soft delete)
     * 
     * @param int $id L'ID du message
     * @return bool true si succès, false sinon
     */
    public function restore(int $id): bool
    {
        // Prépare la requête SQL
        $sql = 'UPDATE posts SET deleted_at = NULL, deleted_by = NULL WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([$id]);
        
        return $success;
    }
}
