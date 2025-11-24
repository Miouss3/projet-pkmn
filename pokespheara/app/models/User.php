<?php
namespace App\Models;

use PDO;

class User {
    protected $pdo;

    public function __construct() {
        $this->pdo = new PDO(
            'mysql:host=mysql;dbname=bdd_pksa;charset=utf8',
            'user',
            'password'
        );
        $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }

    // Récupère tous les utilisateurs
    public function getAll() {
        $stmt = $this->pdo->query('SELECT * FROM user');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    // Récupère un utilisateur par email (pour login)
    public function getUserByEmail(string $email): ?array {
        $stmt = $this->pdo->prepare('SELECT * FROM user WHERE email = ?');
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ?: null; // retourne null si l'utilisateur n'existe pas
    }
}
