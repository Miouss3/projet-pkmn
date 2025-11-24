<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Topic;

/**
 * TopicModelTest - Tests pour le modèle Topic
 * 
 * Ces tests vérifient que le modèle Topic fonctionne correctement
 */
class TopicModelTest extends TestCase
{
    /**
     * Test : Créer une instance du modèle Topic
     * 
     * Vérifie qu'on peut créer une instance du modèle Topic
     */
    public function testCanCreateTopicModelInstance(): void
    {
        // Crée une instance du modèle Topic
        $topicModel = new Topic();
        
        // Vérifie que c'est bien une instance de Topic
        $this->assertInstanceOf(Topic::class, $topicModel);
    }

    /**
     * Test : Vérifier que les méthodes existent
     * 
     * Vérifie que toutes les méthodes nécessaires existent
     */
    public function testTopicModelHasRequiredMethods(): void
    {
        // Crée une instance du modèle Topic
        $topicModel = new Topic();
        
        // Liste des méthodes qui doivent exister
        $requiredMethods = [
            'getAll',
            'findByCategory',
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
                method_exists($topicModel, $method),
                "La méthode $method devrait exister dans Topic"
            );
        }
    }
}

