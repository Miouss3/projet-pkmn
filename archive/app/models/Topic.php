<?php

namespace App\Models;

use App\Database;
use PDO;

/**
 * Topic - Modèle pour gérer les sujets
 * 
 * Contient toutes les méthodes pour interagir avec la table topics
 */
class Topic
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
     * Récupère tous les sujets
     * 
     * @param bool $includeDeleted Si true, inclut aussi les sujets supprimés
     * @return array Liste de tous les sujets
     */
    public function getAll(bool $includeDeleted = false): array
    {
        // Construit la clause WHERE selon si on inclut les supprimés ou non
        $whereClause = '';
        if (!$includeDeleted) {
            $whereClause = 'WHERE t.deleted_at IS NULL';
        }
        
        // Requête SQL avec jointures pour récupérer l'auteur, la catégorie et compter les messages
        $sql = "SELECT t.*, 
                       u.username as author_name,
                       c.name as category_name,
                       COUNT(DISTINCT p.id) as post_count,
                       MAX(p.created_at) as last_post_date
                FROM topics t
                LEFT JOIN users u ON t.user_id = u.id
                LEFT JOIN categories c ON t.category_id = c.id
                LEFT JOIN posts p ON t.id = p.topic_id AND p.deleted_at IS NULL
                $whereClause
                GROUP BY t.id, t.created_at
                ORDER BY COALESCE(MAX(p.created_at), t.created_at) DESC";
        
        $req = $this->pdo->query($sql);
        
        // Récupère tous les résultats
        $topics = $req->fetchAll(PDO::FETCH_ASSOC);
        
        return $topics;
    }

    /**
     * Récupère tous les sujets d'une catégorie
     * 
     * @param int $categoryId L'ID de la catégorie
     * @return array Liste des sujets de la catégorie
     */
    public function findByCategory(int $categoryId): array
    {
        // Prépare la requête SQL avec jointures
        $sql = 'SELECT t.*, 
                       u.username as author_name,
                       COUNT(DISTINCT p.id) as post_count,
                       MAX(p.created_at) as last_post_date
                FROM topics t
                LEFT JOIN users u ON t.user_id = u.id
                LEFT JOIN posts p ON t.id = p.topic_id AND p.deleted_at IS NULL
                WHERE t.category_id = ? AND t.deleted_at IS NULL
                GROUP BY t.id, t.created_at
                ORDER BY COALESCE(MAX(p.created_at), t.created_at) DESC';
        
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête avec l'ID de la catégorie
        $req->execute([$categoryId]);
        
        // Récupère tous les résultats
        $topics = $req->fetchAll(PDO::FETCH_ASSOC);
        
        return $topics;
    }

    /**
     * Trouve un sujet par son ID
     * 
     * @param int $id L'ID du sujet
     * @param bool $includeDeleted Si true, inclut aussi les sujets supprimés
     * @return array|null Les données du sujet ou null si pas trouvé
     */
    public function findById(int $id, bool $includeDeleted = false): ?array
    {
        // Construit la clause WHERE selon si on inclut les supprimés ou non
        $whereClause = 'WHERE t.id = ?';
        if (!$includeDeleted) {
            $whereClause = 'WHERE t.id = ? AND t.deleted_at IS NULL';
        }
        
        // Prépare la requête SQL avec jointures
        $sql = "SELECT t.*, 
                       u.username as author_name,
                       c.name as category_name
                FROM topics t
                LEFT JOIN users u ON t.user_id = u.id
                LEFT JOIN categories c ON t.category_id = c.id
                $whereClause";
        
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête avec l'ID
        $req->execute([$id]);
        
        // Récupère le résultat
        $topic = $req->fetch(PDO::FETCH_ASSOC);
        
        // Si un sujet a été trouvé, on le retourne
        if ($topic) {
            return $topic;
        } else {
            return null;
        }
    }

    /**
     * Crée un nouveau sujet
     * 
     * @param array $data Les données du sujet (title, content, category_id, user_id)
     * @return int L'ID du nouveau sujet créé
     */
    public function create(array $data): int
    {
        // Prépare la requête SQL d'insertion
        $sql = 'INSERT INTO topics (title, content, category_id, user_id, created_at) 
                VALUES (?, ?, ?, ?, NOW())';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête avec les données
        $req->execute([
            $data['title'],
            $data['content'],
            $data['category_id'],
            $data['user_id']
        ]);
        
        // Récupère l'ID du nouveau sujet créé
        $newTopicId = $this->pdo->lastInsertId();
        
        // Convertit en entier et retourne
        return (int) $newTopicId;
    }

    /**
     * Met à jour un sujet
     * 
     * @param int $id L'ID du sujet
     * @param array $data Les nouvelles données (title, content)
     * @return bool true si succès, false sinon
     */
    public function update(int $id, array $data): bool
    {
        // Prépare la requête SQL
        $sql = 'UPDATE topics SET title = ?, content = ? WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([
            $data['title'],
            $data['content'],
            $id
        ]);
        
        return $success;
    }

    /**
     * Supprime définitivement un sujet
     * 
     * @param int $id L'ID du sujet
     * @return bool true si succès, false sinon
     */
    public function delete(int $id): bool
    {
        // Prépare la requête SQL
        $sql = 'DELETE FROM topics WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([$id]);
        
        return $success;
    }

    /**
     * Supprime "en douceur" un sujet (soft delete)
     * Le sujet n'est pas vraiment supprimé, juste marqué comme supprimé
     * 
     * @param int $id L'ID du sujet
     * @param int $deletedBy L'ID de l'utilisateur qui supprime
     * @return bool true si succès, false sinon
     */
    public function softDelete(int $id, int $deletedBy): bool
    {
        // Prépare la requête SQL
        $sql = 'UPDATE topics SET deleted_at = NOW(), deleted_by = ? WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([$deletedBy, $id]);
        
        return $success;
    }

    /**
     * Restaure un sujet supprimé (annule le soft delete)
     * 
     * @param int $id L'ID du sujet
     * @return bool true si succès, false sinon
     */
    public function restore(int $id): bool
    {
        // Prépare la requête SQL
        $sql = 'UPDATE topics SET deleted_at = NULL, deleted_by = NULL WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([$id]);
        
        return $success;
    }
}
