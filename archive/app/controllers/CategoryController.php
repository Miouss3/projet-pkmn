<?php

namespace App\Controllers;

use App\Models\Category;
use App\Models\Topic;

/**
 * CategoryController - Gère les catégories
 */
class CategoryController extends BaseController
{
    /**
     * Affiche une catégorie et ses sujets
     * 
     * @param int $id L'ID de la catégorie
     */
    public function show(int $id): void
    {
        // Crée les modèles nécessaires
        $categoryModel = new Category();
        $topicModel = new Topic();

        // Récupère la catégorie
        $category = $categoryModel->findById($id);
        
        // Si la catégorie n'existe pas, on affiche une erreur
        if (!$category) {
            $_SESSION['error'] = 'Catégorie non trouvée';
            $this->redirect('/');
            return;
        }

        // Récupère tous les sujets de cette catégorie
        $topics = $topicModel->findByCategory($id);
        
        // Affiche la page de la catégorie
        $this->render('category/show', [
            'category' => $category,
            'topics' => $topics
        ]);
    }

    /**
     * Affiche le formulaire de création d'une catégorie
     */
    public function create(): void
    {
        // Vérifie que l'utilisateur est connecté
        $this->requireAuth();
        
        // Affiche le formulaire
        $this->render('category/create');
    }

    /**
     * Traite la création d'une catégorie (quand le formulaire est soumis)
     */
    public function store(): void
    {
        // Vérifie que l'utilisateur est connecté
        $this->requireAuth();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/categories/create');
            return;
        }

        // Récupère les données du formulaire
        $name = '';
        if (isset($_POST['name'])) {
            $name = trim($_POST['name']);
        }
        
        $description = '';
        if (isset($_POST['description'])) {
            $description = trim($_POST['description']);
        }

        // Validation : vérifie que le nom n'est pas vide
        if (empty($name)) {
            $_SESSION['error'] = 'Le nom de la catégorie est requis';
            $this->redirect('/categories/create');
            return;
        }

        // Crée la catégorie
        $categoryModel = new Category();
        $categoryModel->create([
            'name' => $name,
            'description' => $description
        ]);

        // Affiche un message de succès
        $_SESSION['success'] = 'Catégorie créée avec succès !';
        
        // Redirige vers l'accueil
        $this->redirect('/');
    }
}
