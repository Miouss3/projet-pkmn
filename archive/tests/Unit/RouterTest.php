<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Router;

/**
 * RouterTest - Tests pour la classe Router
 * 
 * Ces tests vérifient que le Router fonctionne correctement
 */
class RouterTest extends TestCase
{
    /**
     * Nettoie les routes avant chaque test
     * 
     * Cette méthode est appelée avant chaque test pour s'assurer
     * que les tests ne se marchent pas dessus
     */
    protected function setUp(): void
    {
        // Utilise la réflexion pour accéder à la propriété privée $routes
        $reflection = new \ReflectionClass(Router::class);
        $property = $reflection->getProperty('routes');
        $property->setAccessible(true);
        $property->setValue(null, []);
    }

    /**
     * Test : Enregistrer une route GET
     * 
     * Vérifie qu'on peut enregistrer une route GET et qu'elle est bien stockée
     */
    public function testRegisterGetRoute(): void
    {
        // Enregistre une route GET
        Router::get('/test', 'TestController@index');
        
        // Utilise la réflexion pour vérifier que la route est bien enregistrée
        $reflection = new \ReflectionClass(Router::class);
        $property = $reflection->getProperty('routes');
        $property->setAccessible(true);
        $routes = $property->getValue(null);
        
        // Vérifie que la route existe
        $this->assertArrayHasKey('GET', $routes);
        $this->assertArrayHasKey('test', $routes['GET']);
        $this->assertEquals('TestController@index', $routes['GET']['test']);
    }

    /**
     * Test : Enregistrer une route POST
     * 
     * Vérifie qu'on peut enregistrer une route POST et qu'elle est bien stockée
     */
    public function testRegisterPostRoute(): void
    {
        // Enregistre une route POST
        Router::post('/login', 'AuthController@login');
        
        // Utilise la réflexion pour vérifier que la route est bien enregistrée
        $reflection = new \ReflectionClass(Router::class);
        $property = $reflection->getProperty('routes');
        $property->setAccessible(true);
        $routes = $property->getValue(null);
        
        // Vérifie que la route existe
        $this->assertArrayHasKey('POST', $routes);
        $this->assertArrayHasKey('login', $routes['POST']);
        $this->assertEquals('AuthController@login', $routes['POST']['login']);
    }

    /**
     * Test : Normaliser une URI
     * 
     * Vérifie que les URIs sont bien normalisées (sans slashes au début/fin)
     */
    public function testNormalizeUri(): void
    {
        // Test avec slash au début
        Router::get('/test', 'TestController@index');
        
        $reflection = new \ReflectionClass(Router::class);
        $property = $reflection->getProperty('routes');
        $property->setAccessible(true);
        $routes = $property->getValue(null);
        
        // Vérifie que la route est stockée sans slash
        $this->assertArrayHasKey('test', $routes['GET']);
        $this->assertArrayNotHasKey('/test', $routes['GET']);
    }

    /**
     * Test : Route avec paramètres dynamiques
     * 
     * Vérifie qu'on peut enregistrer une route avec des paramètres (ex: {id})
     */
    public function testRouteWithParameters(): void
    {
        // Enregistre une route avec un paramètre
        Router::get('/topics/{id}', 'TopicController@show');
        
        $reflection = new \ReflectionClass(Router::class);
        $property = $reflection->getProperty('routes');
        $property->setAccessible(true);
        $routes = $property->getValue(null);
        
        // Vérifie que la route est bien enregistrée
        $this->assertArrayHasKey('topics/{id}', $routes['GET']);
    }
}

