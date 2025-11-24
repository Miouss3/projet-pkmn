<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\User;
use App\Database;
use PDO;

/**
 * UserModelTest - Tests pour le modèle User
 * 
 * Ces tests vérifient que le modèle User fonctionne correctement
 * 
 * Note : Ces tests nécessitent une base de données de test
 */
class UserModelTest extends TestCase
{
    private $pdo;
    private $userModel;

    /**
     * Configuration avant chaque test
     * 
     * Crée une connexion à la base de données de test
     */
    protected function setUp(): void
    {
        // Pour les tests, on pourrait utiliser une base de données de test
        // Ici, on va juste vérifier que le modèle peut être instancié
        
        // Note : Pour des vrais tests, il faudrait une base de données de test
        // et créer/supprimer les données avant/après chaque test
    }

    /**
     * Test : Créer une instance du modèle User
     * 
     * Vérifie qu'on peut créer une instance du modèle User
     */
    public function testCanCreateUserModelInstance(): void
    {
        // Crée une instance du modèle User
        $userModel = new User();
        
        // Vérifie que c'est bien une instance de User
        $this->assertInstanceOf(User::class, $userModel);
    }

    /**
     * Test : Vérifier le mot de passe
     * 
     * Vérifie que la méthode verifyPassword fonctionne correctement
     */
    public function testVerifyPassword(): void
    {
        // Crée une instance du modèle User
        $userModel = new User();
        
        // Mot de passe en clair
        $password = 'monMotDePasse123';
        
        // Hash le mot de passe
        $hash = password_hash($password, PASSWORD_DEFAULT);
        
        // Vérifie que le mot de passe correspond au hash
        $isValid = $userModel->verifyPassword($password, $hash);
        $this->assertTrue($isValid);
        
        // Vérifie qu'un mauvais mot de passe ne correspond pas
        $isInvalid = $userModel->verifyPassword('mauvaisMotDePasse', $hash);
        $this->assertFalse($isInvalid);
    }

    /**
     * Test : Vérifier qu'un utilisateur est admin
     * 
     * Note : Ce test nécessite une base de données avec des utilisateurs
     * Pour l'instant, on vérifie juste que la méthode existe
     */
    public function testIsAdminMethodExists(): void
    {
        // Crée une instance du modèle User
        $userModel = new User();
        
        // Vérifie que la méthode isAdmin existe
        $this->assertTrue(method_exists($userModel, 'isAdmin'));
    }

    /**
     * Test : Vérifier qu'un utilisateur est banni
     * 
     * Note : Ce test nécessite une base de données avec des utilisateurs
     * Pour l'instant, on vérifie juste que la méthode existe
     */
    public function testIsBannedMethodExists(): void
    {
        // Crée une instance du modèle User
        $userModel = new User();
        
        // Vérifie que la méthode isBanned existe
        $this->assertTrue(method_exists($userModel, 'isBanned'));
    }
}

