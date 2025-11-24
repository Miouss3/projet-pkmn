<?php
namespace App\Controllers;

use App\Models\User;

class UserController extends Controller
{
    private $userModel;

    public function __construct(){
        if (session_status() === PHP_SESSION_NONE)
        session_start();
        
       $this->userModel = new User();
    }

    // Liste des utilisateurs ou page d'accueil
    public function index()
    {
        $users = $this->userModel->getAll();
        $this->view('home', ['users' => $users]);
    }

    // Formulaire de création
    public function create()
    {
        $this->view('create');
    }

    // Sauvegarde d’un nouvel utilisateur + connexion automatique
    public function store()
    {
        $nom = $_POST['nom'] ?? '';
        $email = $_POST['email'] ?? '';
        $password = $_POST['password'] ?? '';

        $pdo = new \PDO('mysql:host=mysql;dbname=bdd_pksa', 'user', 'password');
        $stmt = $pdo->prepare("INSERT INTO user (username, email, password) VALUES (?, ?, ?)");
        $stmt->execute([$nom, $email, password_hash($password, PASSWORD_DEFAULT)]);

        // Stocke l’utilisateur en session
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['user_email'] = $email;
        $_SESSION['user_name'] = $nom;
        $_SESSION['logged_in'] = true;

        // Reste sur la page d'accueil, bouton profil disponible
        header("Location: /home");
        exit;
    }

    // Connexion
    public function login()
    {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $errors = [];

            if (empty($email)) $errors[] = "L'email est requis";
            elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Format d'email invalide";

            if (empty($password)) $errors[] = "Le mot de passe est requis";

            if (empty($errors)) {
                $user = $this->userModel->getUserByEmail($email);

                if ($user && password_verify($password, $user['password'])) {
                    $_SESSION['user_id'] = $user['id'];
                    $_SESSION['user_email'] = $user['email'];
                    $_SESSION['user_name'] = $user['username'];
                    $_SESSION['logged_in'] = true;

                    // Reste sur la page d'accueil, bouton profil disponible
                    header("Location: /home");
                    exit;
                    return;
                } else {
                    $errors[] = "Email ou mot de passe incorrect";
                }
            }

            $this->view('login', ['errors' => $errors, 'email' => $email]);
        } else {
            $this->view('login');
        }
    }

    // Page profil accessible via le bouton sur la page d'accueil
   public function profile()
{
    if (!isset($_SESSION['logged_in']) || !$_SESSION['logged_in']) {
        echo "Vous devez être connecté pour voir votre profil.";
        return;
    }

    // Récupérer les infos complètes de l'utilisateur depuis la base
    $user = $this->userModel->getUserByEmail($_SESSION['user_email']);
    
    // Vérifier si l'utilisateur existe et récupérer la date de création
    $created_at = $user['created_at'] ?? null;

    $this->view('profile', [
        'username'   => $_SESSION['user_name'],
        'email'      => $_SESSION['user_email'],
        'created_at' => $created_at
    ]);
}


    // Déconnexion
    public function logout()
    {
        session_destroy();
        header('Location: /');
        exit;
    }

    // Vérification si l’utilisateur est connecté
    public function isLoggedIn()
    {
        return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
    }
}

// Contrôleur parent
class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }
}
