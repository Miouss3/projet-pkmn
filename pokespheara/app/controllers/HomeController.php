<?php
namespace App\Controllers;

class HomeController {
    public function index() {
        // Chemin vers la vue home.php
        $homePath = __DIR__ . '/../views/home.php';

        if (file_exists($homePath)) {
            require $homePath;
        } else {
            echo "Page d'accueil non trouvée !";
        }
    }
}
