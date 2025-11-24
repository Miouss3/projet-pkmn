<?php

namespace App\Controllers;

/**
 * BaseController - Classe de base pour tous les contrôleurs
 * 
 * Contient les méthodes communes à tous les contrôleurs
 */
class BaseController
{
    /**
     * Génère une URL avec le chemin de base de l'application
     * Utile quand l'app est dans un sous-dossier (ex: /myapi/)
     * 
     * @param string $path Le chemin à ajouter (ex: '/login')
     * @return string L'URL complète (ex: '/myapi/login')
     */
    protected function url(string $path = ''): string
    {
        // Récupère le chemin du script (ex: /myapi/index.php)
        $scriptPath = '';
        if (isset($_SERVER['SCRIPT_NAME'])) {
            $scriptPath = $_SERVER['SCRIPT_NAME'];
        }
        
        // Extrait le dossier (ex: /myapi)
        $basePath = dirname($scriptPath);
        
        // Si on est à la racine, dirname retourne '/' ou '\'
        if ($basePath === '/' || $basePath === '\\' || $basePath === '.') {
            $basePath = '';
        }
        
        // Normalise les slashes (remplace \ par /)
        $basePath = str_replace('\\', '/', $basePath);
        $basePath = rtrim($basePath, '/');
        
        // Nettoie le chemin demandé (enlève le slash au début)
        $path = ltrim($path, '/');
        
        // Si le chemin est vide, retourne le basePath ou '/'
        if (empty($path)) {
            if (empty($basePath)) {
                return '/';
            } else {
                return $basePath;
            }
        }
        
        // Retourne le chemin complet
        return $basePath . '/' . $path;
    }

    /**
     * Affiche une vue avec des données
     * 
     * @param string $view Le nom de la vue (ex: 'home/index')
     * @param array $data Les données à passer à la vue
     */
    protected function render(string $view, array $data = []): void
    {
        // Extrait les données pour les rendre disponibles dans la vue
        // Exemple: ['title' => 'Accueil'] devient $title = 'Accueil'
        extract($data);
        
        // Crée une fonction url() disponible dans toutes les vues
        // Cette fonction appelle la méthode url() de ce contrôleur
        $url = function($path = '') {
            return $this->url($path);
        };
        
        // Charge le header (en-tête de page)
        require __DIR__ . '/../views/layout/header.php';
        
        // Charge la vue demandée
        require __DIR__ . '/../views/' . $view . '.php';
        
        // Charge le footer (pied de page)
        require __DIR__ . '/../views/layout/footer.php';
    }

    /**
     * Redirige vers une autre page
     * 
     * @param string $url L'URL vers laquelle rediriger
     */
    protected function redirect(string $url): void
    {
        // Si l'URL ne commence pas par http:// ou https://, c'est une URL relative
        // On doit donc ajouter le chemin de base
        $isAbsoluteUrl = false;
        if (preg_match('/^https?:\/\//', $url)) {
            $isAbsoluteUrl = true;
        }
        
        if (!$isAbsoluteUrl) {
            $url = $this->url($url);
        }
        
        // Envoie l'en-tête HTTP de redirection
        header("Location: $url");
        
        // Arrête l'exécution du script
        exit;
    }

    /**
     * Vérifie si l'utilisateur est connecté
     * 
     * @return bool true si connecté, false sinon
     */
    protected function isLoggedIn(): bool
    {
        if (isset($_SESSION['user_id'])) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * Vérifie que l'utilisateur est connecté, sinon redirige vers la page de connexion
     */
    protected function requireAuth(): void
    {
        if (!$this->isLoggedIn()) {
            $this->redirect('/login');
        }
    }

    /**
     * Récupère l'ID de l'utilisateur connecté
     * 
     * @return int|null L'ID de l'utilisateur ou null s'il n'est pas connecté
     */
    protected function getUserId(): ?int
    {
        if (isset($_SESSION['user_id'])) {
            return $_SESSION['user_id'];
        } else {
            return null;
        }
    }

    /**
     * Vérifie que l'utilisateur est administrateur, sinon redirige
     */
    protected function requireAdmin(): void
    {
        // D'abord, vérifie que l'utilisateur est connecté
        $this->requireAuth();
        
        // Récupère l'ID de l'utilisateur
        $userId = $this->getUserId();
        
        // Crée un modèle User pour vérifier les droits
        $userModel = new \App\Models\User();
        
        // Vérifie si l'utilisateur est admin
        $isAdmin = $userModel->isAdmin($userId);
        
        if (!$isAdmin) {
            $_SESSION['error'] = 'Accès refusé. Vous devez être administrateur.';
            $this->redirect('/');
        }
    }

    /**
     * Vérifie si l'utilisateur connecté est administrateur
     * 
     * @return bool true si admin, false sinon
     */
    protected function isAdmin(): bool
    {
        // Si l'utilisateur n'est pas connecté, il n'est pas admin
        if (!$this->isLoggedIn()) {
            return false;
        }
        
        // Récupère l'ID de l'utilisateur
        $userId = $this->getUserId();
        
        // Crée un modèle User pour vérifier les droits
        $userModel = new \App\Models\User();
        
        // Retourne true si l'utilisateur est admin
        return $userModel->isAdmin($userId);
    }
}
