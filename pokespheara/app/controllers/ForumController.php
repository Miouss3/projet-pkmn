<?php
namespace App\Controllers;

require __DIR__ . '/../../app/models/Category.php';
require __DIR__ . '/../../app/models/ForumTopic.php';


class ForumController
{
    private $db;

    public function __construct($db)
    {
        // $db = instance PDO
        $this->db = $db;
    }

    // Affiche le formulaire de création de post avec les catégories
    public function create()
    {
        // Récupérer les catégories depuis la table 'categories'
        $categoryModel = new Category($this->db);
        $categories = $categoryModel->getAll();
        
        

        // Charger la vue avec les catégories
        require __DIR__ . '/../../views/createpost.php';
    }

    // Enregistre un nouveau post dans forum_topics
   public function store()
{
    
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $title = trim($_POST['title'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $category_id = intval($_POST['category_id'] ?? 0);

        $errors = [];
        if (empty($title)) $errors[] = 'Le titre est obligatoire.';
        if (empty($content)) $errors[] = 'Le contenu est obligatoire.';
        if ($category_id <= 0) $errors[] = 'Veuillez choisir une catégorie.';

        if (!empty($errors)) {
            $_SESSION['errors'] = $errors;
            header('Location: /posts/create');
            exit;
        }

        $forumTopicModel = new ForumTopic($this->db);
        $forumTopicModel->create([
            'title'       => $title,
            'content'     => $content,
            'category_id' => $category_id,
            'created_at'  => date('Y-m-d H:i:s'),
        ]);

        header('Location: /posts');
        exit;
    }
}
}
