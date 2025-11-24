<?php

namespace App\Controllers;

use App\Models\Category;

/**
 * HomeController - Gère la page d'accueil
 */
class HomeController extends BaseController
{
    /**
     * Affiche la page d'accueil avec toutes les catégories
     */
    public function index(): void
    {
        // Crée un modèle Category
        $categoryModel = new Category();
        
        // Récupère toutes les catégories
        $categories = $categoryModel->getAll();
        
        // Affiche la page d'accueil avec les catégories
        $this->render('home/index', ['categories' => $categories]);
    }
}
