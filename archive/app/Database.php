<?php

namespace App;

use PDO;
use PDOException;

/**
 * Database - Gère la connexion à la base de données
 * 
 * Utilise le pattern Singleton pour n'avoir qu'une seule connexion
 */
class Database
{
    // Variable statique pour stocker l'instance unique
    private static $instance = null;
    
    // Variable pour stocker la connexion PDO
    private $pdo;

    /**
     * Constructeur privé pour empêcher la création directe d'instances
     */
    private function __construct()
    {
        // Charge la configuration de la base de données
        $config = require __DIR__ . '/../config/config.php';
        
        // Construit la chaîne de connexion (DSN)
        $host = $config['host'];
        $dbname = $config['dbname'];
        $charset = $config['charset'];
        $dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

        // Tente de se connecter à la base de données
        try {
            $username = $config['username'];
            $password = $config['password'];
            $this->pdo = new PDO($dsn, $username, $password);
            
            // Configure PDO pour afficher les erreurs
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            // Configure PDO pour retourner les résultats sous forme de tableau associatif
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            // Si erreur, on lance une exception avec un message clair
            $errorMessage = "Erreur de connexion à la base de données : " . $e->getMessage();
            throw new PDOException($errorMessage);
        }
    }

    /**
     * Récupère l'instance unique de Database (Singleton)
     * 
     * @return Database L'instance unique
     */
    public static function getInstance(): Database
    {
        // Si l'instance n'existe pas encore, on la crée
        if (self::$instance === null) {
            self::$instance = new self();
        }
        
        // Retourne l'instance existante
        return self::$instance;
    }

    /**
     * Récupère la connexion PDO
     * 
     * @return PDO La connexion à la base de données
     */
    public function getConnection(): PDO
    {
        return $this->pdo;
    }
}
