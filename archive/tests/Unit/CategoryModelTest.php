<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Models\Category;

/**
 * CategoryModelTest - Tests pour le modèle Category
 * 
 * Ces tests vérifient que le modèle Category fonctionne correctement
 */
class CategoryModelTest extends TestCase
{
    /**
     * Test : Créer une instance du modèle Category
     * 
     * Vérifie qu'on peut créer une instance du modèle Category
     */
    public function testCanCreateCategoryModelInstance(): void
    {
        // Crée une instance du modèle Category
        $categoryModel = new Category();
        
        // Vérifie que c'est bien une instance de Category
        $this->assertInstanceOf(Category::class, $categoryModel);
    }

    /**
     * Test : Vérifier que les méthodes existent
     * 
     * Vérifie que toutes les méthodes nécessaires existent
     */
    public function testCategoryModelHasRequiredMethods(): void
    {
        // Crée une instance du modèle Category
        $categoryModel = new Category();
        
        // Liste des méthodes qui doivent exister
        $requiredMethods = [
            'getAll',
            'findById',
            'create',
            'update',
            'delete'
        ];
        
        // Vérifie que chaque méthode existe
        foreach ($requiredMethods as $method) {
            $this->assertTrue(
                method_exists($categoryModel, $method),
                "La méthode $method devrait exister dans Category"
            );
        }
    }
}

