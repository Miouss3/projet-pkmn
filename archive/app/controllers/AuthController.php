<?php

namespace App\Controllers;

use App\Models\User;

/**
 * AuthController - Gère l'authentification (connexion, inscription, déconnexion)
 */
class AuthController extends BaseController
{
    private $userModel;

    /**
     * Constructeur - Initialise le modèle User
     */
    public function __construct()
    {
        $this->userModel = new User();
    }

    /**
     * Affiche la page de connexion
     */
    public function showLogin(): void
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers l'accueil
        if ($this->isLoggedIn()) {
            $this->redirect('/');
            return;
        }
        
        // Affiche la page de connexion
        $this->render('auth/login');
    }

    /**
     * Traite la connexion (quand le formulaire est soumis)
     */
    public function login(): void
    {
        // Vérifie que c'est bien une requête POST (formulaire soumis)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/login');
            return;
        }

        // Récupère les données du formulaire
        $email = '';
        if (isset($_POST['email'])) {
            $email = $_POST['email'];
        }
        
        $password = '';
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        }

        // Vérifie que les champs ne sont pas vides
        if (empty($email) || empty($password)) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs';
            $this->redirect('/login');
            return;
        }

        // Cherche l'utilisateur par son email
        $user = $this->userModel->findByEmail($email);

        // Vérifie si l'utilisateur existe et si le mot de passe est correct
        if (!$user) {
            $_SESSION['error'] = 'Email ou mot de passe incorrect';
            $this->redirect('/login');
            return;
        }
        
        // Vérifie le mot de passe
        $isPasswordCorrect = $this->userModel->verifyPassword($password, $user['password']);
        if (!$isPasswordCorrect) {
            $_SESSION['error'] = 'Email ou mot de passe incorrect';
            $this->redirect('/login');
            return;
        }

        // Vérifie si l'utilisateur est banni
        $isBanned = $this->userModel->isBanned($user['id']);
        if ($isBanned) {
            $_SESSION['error'] = 'Votre compte a été banni. Contactez un administrateur pour plus d\'informations.';
            $this->redirect('/login');
            return;
        }

        // Tout est bon, on connecte l'utilisateur
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['username'] = $user['username'];
        $_SESSION['success'] = 'Connexion réussie !';
        
        // Redirige vers l'accueil
        $this->redirect('/');
    }

    /**
     * Affiche la page d'inscription
     */
    public function showRegister(): void
    {
        // Si l'utilisateur est déjà connecté, on le redirige vers l'accueil
        if ($this->isLoggedIn()) {
            $this->redirect('/');
            return;
        }
        
        // Affiche la page d'inscription
        $this->render('auth/register');
    }

    /**
     * Traite l'inscription (quand le formulaire est soumis)
     */
    public function register(): void
    {
        // Vérifie que c'est bien une requête POST (formulaire soumis)
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            $this->redirect('/register');
            return;
        }

        // Récupère les données du formulaire
        $username = '';
        if (isset($_POST['username'])) {
            $username = trim($_POST['username']);
        }
        
        $email = '';
        if (isset($_POST['email'])) {
            $email = trim($_POST['email']);
        }
        
        $password = '';
        if (isset($_POST['password'])) {
            $password = $_POST['password'];
        }
        
        $passwordConfirm = '';
        if (isset($_POST['password_confirm'])) {
            $passwordConfirm = $_POST['password_confirm'];
        }

        // Validation : vérifie que tous les champs sont remplis
        if (empty($username) || empty($email) || empty($password)) {
            $_SESSION['error'] = 'Veuillez remplir tous les champs';
            $this->redirect('/register');
            return;
        }

        // Validation : vérifie que les mots de passe correspondent
        if ($password !== $passwordConfirm) {
            $_SESSION['error'] = 'Les mots de passe ne correspondent pas';
            $this->redirect('/register');
            return;
        }

        // Validation : vérifie la longueur du mot de passe
        if (strlen($password) < 6) {
            $_SESSION['error'] = 'Le mot de passe doit contenir au moins 6 caractères';
            $this->redirect('/register');
            return;
        }

        // Vérifie si l'email existe déjà
        $existingUser = $this->userModel->findByEmail($email);
        if ($existingUser) {
            $_SESSION['error'] = 'Cet email est déjà utilisé';
            $this->redirect('/register');
            return;
        }

        // Crée le nouvel utilisateur
        $userData = [
            'username' => $username,
            'email' => $email,
            'password' => $password
        ];
        $userId = $this->userModel->create($userData);

        // Connecte automatiquement l'utilisateur après l'inscription
        $_SESSION['user_id'] = $userId;
        $_SESSION['username'] = $username;
        $_SESSION['success'] = 'Inscription réussie ! Bienvenue sur le forum Pokémon !';
        
        // Redirige vers l'accueil
        $this->redirect('/');
    }

    /**
     * Déconnecte l'utilisateur
     */
    public function logout(): void
    {
        // Détruit la session
        session_destroy();
        
        // Redirige vers la page de connexion
        $this->redirect('/login');
    }
}
