<?php

namespace App\Models;

use App\Database;
use PDO;

/**
 * Category - Modèle pour gérer les catégories
 * 
 * Contient toutes les méthodes pour interagir avec la table categories
 */
class Category
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
     * Récupère toutes les catégories avec le nombre de sujets et messages
     * 
     * @return array Liste de toutes les catégories
     */
    public function getAll(): array
    {
        // Requête SQL complexe avec des jointures pour compter les sujets et messages
        $sql = 'SELECT c.*, 
                       COUNT(DISTINCT t.id) as topic_count,
                       COUNT(DISTINCT p.id) as post_count
                FROM categories c
                LEFT JOIN topics t ON c.id = t.category_id
                LEFT JOIN posts p ON t.id = p.topic_id
                GROUP BY c.id
                ORDER BY c.name ASC';
        
        $req = $this->pdo->query($sql);
        
        // Récupère tous les résultats
        $categories = $req->fetchAll(PDO::FETCH_ASSOC);
        
        return $categories;
    }

    /**
     * Trouve une catégorie par son ID
     * 
     * @param int $id L'ID de la catégorie
     * @return array|null Les données de la catégorie ou null si pas trouvée
     */
    public function findById(int $id): ?array
    {
        // Prépare la requête SQL
        $sql = 'SELECT * FROM categories WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête avec l'ID
        $req->execute([$id]);
        
        // Récupère le résultat
        $category = $req->fetch(PDO::FETCH_ASSOC);
        
        // Si une catégorie a été trouvée, on la retourne
        if ($category) {
            return $category;
        } else {
            return null;
        }
    }

    /**
     * Crée une nouvelle catégorie
     * 
     * @param array $data Les données de la catégorie (name, description)
     * @return int L'ID de la nouvelle catégorie créée
     */
    public function create(array $data): int
    {
        // Prépare la requête SQL d'insertion
        $sql = 'INSERT INTO categories (name, description, created_at) 
                VALUES (?, ?, NOW())';
        $req = $this->pdo->prepare($sql);
        
        // Récupère la description (peut être vide)
        $description = null;
        if (isset($data['description'])) {
            $description = $data['description'];
        }
        
        // Exécute la requête avec les données
        $req->execute([
            $data['name'],
            $description
        ]);
        
        // Récupère l'ID de la nouvelle catégorie créée
        $newCategoryId = $this->pdo->lastInsertId();
        
        // Convertit en entier et retourne
        return (int) $newCategoryId;
    }

    /**
     * Met à jour une catégorie
     * 
     * @param int $id L'ID de la catégorie
     * @param array $data Les nouvelles données (name, description)
     * @return bool true si succès, false sinon
     */
    public function update(int $id, array $data): bool
    {
        // Prépare la requête SQL
        $sql = 'UPDATE categories SET name = ?, description = ? WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Récupère la description (peut être vide)
        $description = null;
        if (isset($data['description'])) {
            $description = $data['description'];
        }
        
        // Exécute la requête
        $success = $req->execute([
            $data['name'],
            $description,
            $id
        ]);
        
        return $success;
    }

    /**
     * Supprime une catégorie
     * 
     * @param int $id L'ID de la catégorie
     * @return bool true si succès, false sinon
     */
    public function delete(int $id): bool
    {
        // Prépare la requête SQL
        $sql = 'DELETE FROM categories WHERE id = ?';
        $req = $this->pdo->prepare($sql);
        
        // Exécute la requête
        $success = $req->execute([$id]);
        
        return $success;
    }
}
