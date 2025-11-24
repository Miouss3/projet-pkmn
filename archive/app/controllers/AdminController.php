<?php

namespace App\Controllers;

use App\Models\User;
use App\Models\Topic;
use App\Models\Post;
use App\Models\Category;
use App\Database;
use PDO;

/**
 * AdminController - Gère l'administration du forum
 * 
 * Toutes les méthodes nécessitent d'être administrateur
 */
class AdminController extends BaseController
{
    /**
     * Affiche une vue admin avec le layout admin
     * 
     * @param string $view Le nom de la vue (ex: 'dashboard')
     * @param array $data Les données à passer à la vue
     */
    protected function renderAdmin(string $view, array $data = []): void
    {
        // Extrait les données pour les rendre disponibles dans la vue
        extract($data);
        
        // Crée une fonction url() disponible dans toutes les vues admin
        $url = function($path = '') {
            return $this->url($path);
        };
        
        // Charge le header admin
        require __DIR__ . '/../views/admin/layout/header.php';
        
        // Charge la vue admin
        require __DIR__ . '/../views/admin/' . $view . '.php';
        
        // Charge le footer admin
        require __DIR__ . '/../views/admin/layout/footer.php';
    }

    /**
     * Affiche le tableau de bord admin avec les statistiques
     */
    public function dashboard(): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Crée les modèles nécessaires
        $userModel = new User();
        $topicModel = new Topic();
        $postModel = new Post();
        $categoryModel = new Category();

        // Récupère toutes les données
        $allUsers = $userModel->getAll();
        $allTopics = $topicModel->getAll(true); // Inclut les supprimés
        $allCategories = $categoryModel->getAll();

        // Compte les utilisateurs bannis
        $bannedCount = 0;
        foreach ($allUsers as $user) {
            if ($user['banned']) {
                $bannedCount++;
            }
        }

        // Crée le tableau de statistiques
        $stats = [
            'total_users' => count($allUsers),
            'total_topics' => count($allTopics),
            'total_categories' => count($allCategories),
            'banned_users' => $bannedCount
        ];

        // Récupère les 10 derniers sujets
        $recentTopics = array_slice($allTopics, 0, 10);

        // Récupère les 10 derniers utilisateurs
        $recentUsers = array_slice($allUsers, 0, 10);

        // Affiche le dashboard
        $this->renderAdmin('dashboard', [
            'title' => 'Dashboard',
            'stats' => $stats,
            'recentTopics' => $recentTopics,
            'recentUsers' => $recentUsers
        ]);
    }

    // ========== GESTION DES SUJETS ==========

    /**
     * Affiche la liste de tous les sujets (y compris supprimés)
     */
    public function topics(): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Crée un modèle Topic
        $topicModel = new Topic();
        
        // Récupère tous les sujets (y compris les supprimés)
        $topics = $topicModel->getAll(true);

        // Affiche la page
        $this->renderAdmin('topics', ['title' => 'Sujets', 'topics' => $topics]);
    }

    /**
     * Supprime un sujet (soft delete)
     * 
     * @param int $id L'ID du sujet
     */
    public function deleteTopic(int $id): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/topics');
            return;
        }

        // Récupère l'ID de l'admin qui supprime
        $adminId = $this->getUserId();

        // Supprime le sujet (soft delete)
        $topicModel = new Topic();
        $topicModel->softDelete($id, $adminId);

        // Affiche un message de succès
        $_SESSION['success'] = 'Sujet supprimé avec succès';
        
        // Redirige vers la liste des sujets
        $this->redirect('/admin/topics');
    }

    /**
     * Restaure un sujet supprimé
     * 
     * @param int $id L'ID du sujet
     */
    public function restoreTopic(int $id): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/topics');
            return;
        }

        // Restaure le sujet
        $topicModel = new Topic();
        $topicModel->restore($id);

        // Affiche un message de succès
        $_SESSION['success'] = 'Sujet restauré avec succès';
        
        // Redirige vers la liste des sujets
        $this->redirect('/admin/topics');
    }

    // ========== GESTION DES MESSAGES ==========

    /**
     * Affiche la liste de tous les messages
     */
    public function posts(): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Récupère la connexion à la base de données
        $database = Database::getInstance();
        $pdo = $database->getConnection();
        
        // Requête SQL pour récupérer tous les messages avec l'auteur et le sujet
        $sql = 'SELECT p.*, u.username as author_name, t.title as topic_title
                FROM posts p
                LEFT JOIN users u ON p.user_id = u.id
                LEFT JOIN topics t ON p.topic_id = t.id
                ORDER BY p.created_at DESC
                LIMIT 100';
        
        $req = $pdo->query($sql);
        $posts = $req->fetchAll(PDO::FETCH_ASSOC);

        // Affiche la page
        $this->renderAdmin('posts', ['title' => 'Messages', 'posts' => $posts]);
    }

    /**
     * Supprime un message (soft delete)
     * 
     * @param int $id L'ID du message
     */
    public function deletePost(int $id): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/posts');
            return;
        }

        // Récupère l'ID de l'admin qui supprime
        $adminId = $this->getUserId();

        // Supprime le message (soft delete)
        $postModel = new Post();
        $postModel->softDelete($id, $adminId);

        // Affiche un message de succès
        $_SESSION['success'] = 'Message supprimé avec succès';
        
        // Redirige vers la liste des messages
        $this->redirect('/admin/posts');
    }

    /**
     * Restaure un message supprimé
     * 
     * @param int $id L'ID du message
     */
    public function restorePost(int $id): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/posts');
            return;
        }

        // Restaure le message
        $postModel = new Post();
        $postModel->restore($id);

        // Affiche un message de succès
        $_SESSION['success'] = 'Message restauré avec succès';
        
        // Redirige vers la liste des messages
        $this->redirect('/admin/posts');
    }

    // ========== GESTION DES CATÉGORIES ==========

    /**
     * Affiche la liste de toutes les catégories
     */
    public function categories(): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Crée un modèle Category
        $categoryModel = new Category();
        
        // Récupère toutes les catégories
        $categories = $categoryModel->getAll();

        // Affiche la page
        $this->renderAdmin('categories', ['title' => 'Catégories', 'categories' => $categories]);
    }

    /**
     * Affiche le formulaire d'édition d'une catégorie
     * 
     * @param int $id L'ID de la catégorie
     */
    public function editCategory(int $id): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Crée un modèle Category
        $categoryModel = new Category();
        
        // Récupère la catégorie
        $category = $categoryModel->findById($id);

        // Si la catégorie n'existe pas, on affiche une erreur
        if (!$category) {
            $_SESSION['error'] = 'Catégorie non trouvée';
            $this->redirect('/admin/categories');
            return;
        }

        // Affiche le formulaire d'édition
        $this->renderAdmin('category_edit', ['title' => 'Modifier la catégorie', 'category' => $category]);
    }

    /**
     * Met à jour une catégorie
     * 
     * @param int $id L'ID de la catégorie
     */
    public function updateCategory(int $id): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories');
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
            $this->redirect("/admin/categories/$id/edit");
            return;
        }

        // Met à jour la catégorie
        $categoryModel = new Category();
        $categoryModel->update($id, [
            'name' => $name,
            'description' => $description
        ]);

        // Affiche un message de succès
        $_SESSION['success'] = 'Catégorie mise à jour avec succès';
        
        // Redirige vers la liste des catégories
        $this->redirect('/admin/categories');
    }

    /**
     * Supprime une catégorie
     * 
     * @param int $id L'ID de la catégorie
     */
    public function deleteCategory(int $id): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/categories');
            return;
        }

        // Supprime la catégorie
        $categoryModel = new Category();
        $categoryModel->delete($id);

        // Affiche un message de succès
        $_SESSION['success'] = 'Catégorie supprimée avec succès';
        
        // Redirige vers la liste des catégories
        $this->redirect('/admin/categories');
    }

    // ========== GESTION DES UTILISATEURS ==========

    /**
     * Affiche la liste de tous les utilisateurs
     */
    public function users(): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Crée un modèle User
        $userModel = new User();
        
        // Récupère tous les utilisateurs
        $users = $userModel->getAll();

        // Affiche la page
        $this->renderAdmin('users', ['title' => 'Utilisateurs', 'users' => $users]);
    }

    /**
     * Bannit un utilisateur
     * 
     * @param int $id L'ID de l'utilisateur
     */
    public function banUser(int $id): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
            return;
        }

        // Récupère les données du formulaire
        $reason = '';
        if (isset($_POST['reason'])) {
            $reason = trim($_POST['reason']);
        }
        
        $until = null;
        if (isset($_POST['until']) && !empty($_POST['until'])) {
            $until = $_POST['until'];
        }

        // Récupère l'ID de l'admin actuel
        $adminId = $this->getUserId();
        
        // Ne permet pas de se bannir soi-même
        if ($id == $adminId) {
            $_SESSION['error'] = 'Vous ne pouvez pas vous bannir vous-même';
            $this->redirect('/admin/users');
            return;
        }

        // Bannit l'utilisateur
        $userModel = new User();
        $userModel->ban($id, $reason, $until);

        // Affiche un message de succès
        $_SESSION['success'] = 'Utilisateur banni avec succès';
        
        // Redirige vers la liste des utilisateurs
        $this->redirect('/admin/users');
    }

    /**
     * Débannit un utilisateur
     * 
     * @param int $id L'ID de l'utilisateur
     */
    public function unbanUser(int $id): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
            return;
        }

        // Débannit l'utilisateur
        $userModel = new User();
        $userModel->unban($id);

        // Affiche un message de succès
        $_SESSION['success'] = 'Utilisateur débanni avec succès';
        
        // Redirige vers la liste des utilisateurs
        $this->redirect('/admin/users');
    }

    /**
     * Met à jour le rôle d'un utilisateur
     * 
     * @param int $id L'ID de l'utilisateur
     */
    public function updateUserRole(int $id): void
    {
        // Vérifie que l'utilisateur est admin
        $this->requireAdmin();

        // Vérifie que c'est bien une requête POST
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/admin/users');
            return;
        }

        // Récupère le rôle depuis le formulaire
        $role = 'user';
        if (isset($_POST['role'])) {
            $role = $_POST['role'];
        }
        
        // Validation : vérifie que le rôle est valide
        if ($role !== 'user' && $role !== 'admin') {
            $_SESSION['error'] = 'Rôle invalide';
            $this->redirect('/admin/users');
            return;
        }

        // Récupère l'ID de l'admin actuel
        $adminId = $this->getUserId();
        
        // Ne permet pas de changer son propre rôle
        if ($id == $adminId) {
            $_SESSION['error'] = 'Vous ne pouvez pas modifier votre propre rôle';
            $this->redirect('/admin/users');
            return;
        }

        // Met à jour le rôle
        $userModel = new User();
        $userModel->updateRole($id, $role);

        // Affiche un message de succès
        $_SESSION['success'] = 'Rôle de l\'utilisateur mis à jour';
        
        // Redirige vers la liste des utilisateurs
        $this->redirect('/admin/users');
    }
}
