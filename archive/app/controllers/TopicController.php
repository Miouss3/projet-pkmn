<?php

namespace App\Controllers;

use App\Models\Topic;
use App\Models\Category;
use App\Models\Post;

/**
 * TopicController - Gère les sujets
 */
class TopicController extends BaseController
{
    /**
     * Affiche un sujet et ses messages
     * 
     * @param int $id L'ID du sujet
     */
    public function show(int $id): void
    {
        // Crée les modèles nécessaires
        $topicModel = new Topic();
        $postModel = new Post();

        // Récupère le sujet
        $topic = $topicModel->findById($id);
        
        // Si le sujet n'existe pas, on affiche une erreur
        if (!$topic) {
            $_SESSION['error'] = 'Sujet non trouvé';
            $this->redirect('/');
            return;
        }

        // Récupère tous les messages de ce sujet
        $posts = $postModel->findByTopic($id);
        
        // Affiche la page du sujet
        $this->render('topic/show', [
            'topic' => $topic,
            'posts' => $posts
        ]);
    }

    /**
     * Affiche le formulaire de création d'un sujet
     * 
     * @param int|null $categoryId L'ID de la catégorie pré-sélectionnée (optionnel)
     */
    public function create(?int $categoryId = null): void
    {
        // Vérifie que l'utilisateur est connecté
        $this->requireAuth();
        
        // Crée un modèle Category
        $categoryModel = new Category();
        
        // Récupère toutes les catégories
        $categories = $categoryModel->getAll();
        
        // Si une catégorie est pré-sélectionnée, on la récupère
        $selectedCategory = null;
        if ($categoryId) {
            $selectedCategory = $categoryModel->findById($categoryId);
        }
        
        // Affiche le formulaire
        $this->render('topic/create', [
            'categories' => $categories,
            'selectedCategory' => $selectedCategory
        ]);
    }

    /**
     * Traite la création d'un sujet (quand le formulaire est soumis)
     */
    public function store(): void
    {
        // Vérifie que l'utilisateur est connecté
        $this->requireAuth();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/topics/create');
            return;
        }

        // Récupère les données du formulaire
        $title = '';
        if (isset($_POST['title'])) {
            $title = trim($_POST['title']);
        }
        
        $content = '';
        if (isset($_POST['content'])) {
            $content = trim($_POST['content']);
        }
        
        $categoryId = 0;
        if (isset($_POST['category_id'])) {
            $categoryId = (int) $_POST['category_id'];
        }

        // Validation : vérifie que le titre et le contenu ne sont pas vides
        if (empty($title) || empty($content)) {
            $_SESSION['error'] = 'Le titre et le contenu sont requis';
            $this->redirect('/topics/create');
            return;
        }

        // Validation : vérifie qu'une catégorie a été sélectionnée
        if ($categoryId <= 0) {
            $_SESSION['error'] = 'Veuillez sélectionner une catégorie';
            $this->redirect('/topics/create');
            return;
        }

        // Récupère l'ID de l'utilisateur connecté
        $userId = $this->getUserId();

        // Crée le sujet
        $topicModel = new Topic();
        $topicData = [
            'title' => $title,
            'content' => $content,
            'category_id' => $categoryId,
            'user_id' => $userId
        ];
        $topicId = $topicModel->create($topicData);

        // Affiche un message de succès
        $_SESSION['success'] = 'Sujet créé avec succès !';
        
        // Redirige vers le sujet créé
        $this->redirect("/topics/$topicId");
    }
}
