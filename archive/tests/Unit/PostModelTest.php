<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Post;

/**
 * PostModelTest - Tests pour le modèle Post
 * 
 * Ces tests vérifient que le modèle Post fonctionne correctement
 */
class PostModelTest extends TestCase
{
    /**
     * Test : Créer une instance du modèle Post
     * 
     * Vérifie qu'on peut créer une instance du modèle Post
     */
    public function testCanCreatePostModelInstance(): void
    {
        // Crée une instance du modèle Post
        $postModel = new Post();
        
        // Vérifie que c'est bien une instance de Post
        $this->assertInstanceOf(Post::class, $postModel);
    }

    /**
     * Test : Vérifier que les méthodes existent
     * 
     * Vérifie que toutes les méthodes nécessaires existent
     */
    public function testPostModelHasRequiredMethods(): void
    {
        // Crée une instance du modèle Post
        $postModel = new Post();
        
        // Liste des méthodes qui doivent exister
        $requiredMethods = [
            'findByTopic',
            'findById',
            'create',
            'update',
            'delete',
            'softDelete',
            'restore'
        ];
        
        // Vérifie que chaque méthode existe
        foreach ($requiredMethods as $method) {
            $this->assertTrue(
                method_exists($postModel, $method),
                "La méthode $method devrait exister dans Post"
            );
        }
    }
}

