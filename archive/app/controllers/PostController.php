<?php

namespace App\Controllers;

use App\Models\Post;
use App\Models\Topic;

/**
 * PostController - Gère les messages (posts)
 */
class PostController extends BaseController
{
    /**
     * Traite la création d'un message (quand le formulaire est soumis)
     */
    public function store(): void
    {
        // Vérifie que l'utilisateur est connecté
        $this->requireAuth();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/');
            return;
        }

        // Récupère les données du formulaire
        $content = '';
        if (isset($_POST['content'])) {
            $content = trim($_POST['content']);
        }
        
        $topicId = 0;
        if (isset($_POST['topic_id'])) {
            $topicId = (int) $_POST['topic_id'];
        }

        // Validation : vérifie que le contenu n'est pas vide
        if (empty($content)) {
            $_SESSION['error'] = 'Le contenu du message est requis';
            $this->redirect("/topics/$topicId");
            return;
        }

        // Validation : vérifie que le sujet est valide
        if ($topicId <= 0) {
            $_SESSION['error'] = 'Sujet invalide';
            $this->redirect('/');
            return;
        }

        // Vérifie que le sujet existe
        $topicModel = new Topic();
        $topic = $topicModel->findById($topicId);
        
        if (!$topic) {
            $_SESSION['error'] = 'Sujet non trouvé';
            $this->redirect('/');
            return;
        }

        // Récupère l'ID de l'utilisateur connecté
        $userId = $this->getUserId();

        // Crée le message
        $postModel = new Post();
        $postData = [
            'content' => $content,
            'topic_id' => $topicId,
            'user_id' => $userId
        ];
        $postModel->create($postData);

        // Affiche un message de succès
        $_SESSION['success'] = 'Message posté avec succès !';
        
        // Redirige vers le sujet
        $this->redirect("/topics/$topicId");
    }
}
