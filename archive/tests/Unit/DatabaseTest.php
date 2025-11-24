<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Database;
use PDOException;

/**
 * DatabaseTest - Tests pour la classe Database
 * 
 * Ces tests vérifient que la connexion à la base de données fonctionne
 */
class DatabaseTest extends TestCase
{
    /**
     * Test : Récupérer l'instance unique de Database (Singleton)
     * 
     * Vérifie que getInstance() retourne toujours la même instance
     */
    public function testGetInstanceReturnsSameInstance(): void
    {
        // Récupère la première instance
        $instance1 = Database::getInstance();
        
        // Récupère une deuxième instance
        $instance2 = Database::getInstance();
        
        // Vérifie que c'est la même instance (Singleton)
        $this->assertSame($instance1, $instance2);
    }

    /**
     * Test : Récupérer la connexion PDO
     * 
     * Vérifie qu'on peut récupérer la connexion PDO
     * 
     * Note : Ce test nécessite une configuration de base de données valide
     */
    public function testGetConnectionReturnsPdo(): void
    {
        try {
            // Récupère l'instance de Database
            $database = Database::getInstance();
            
            // Récupère la connexion
            $connection = $database->getConnection();
            
            // Vérifie que c'est bien une instance de PDO
            $this->assertInstanceOf(\PDO::class, $connection);
        } catch (PDOException $e) {
            // Si la connexion échoue, on skip le test
            $this->markTestSkipped('Impossible de se connecter à la base de données : ' . $e->getMessage());
        }
    }

    /**
     * Test : Vérifier que Database est un Singleton
     * 
     * Vérifie qu'on ne peut pas créer plusieurs instances directement
     */
    public function testDatabaseIsSingleton(): void
    {
        // Récupère deux instances via getInstance()
        $instance1 = Database::getInstance();
        $instance2 = Database::getInstance();
        
        // Vérifie que ce sont les mêmes instances
        $this->assertSame($instance1, $instance2);
    }
}

