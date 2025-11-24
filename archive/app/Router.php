<?php

namespace App;

/**
 * Router - Gère les routes de l'application
 * 
 * Comment ça marche :
 * 1. On enregistre des routes avec get() ou post()
 * 2. Quand une page est demandée, dispatch() trouve la bonne route
 * 3. On appelle le contrôleur et la méthode correspondante
 */
class Router
{
    // Tableau qui stocke toutes les routes
    // Format : ['GET' => ['login' => 'Controller@method'], 'POST' => [...]]
    private static array $routes = [];

    /**
     * Enregistre une route GET (pour afficher une page)
     * Exemple : Router::get('/login', 'App\Controllers\AuthController@showLogin');
     */
    public static function get(string $uri, string $action): void
    {
        // Nettoie l'URI (enlève les slashes au début et à la fin)
        $uri = self::cleanUri($uri);
        
        // Stocke la route dans le tableau
        self::$routes['GET'][$uri] = $action;
    }

    /**
     * Enregistre une route POST (pour les formulaires)
     * Exemple : Router::post('/login', 'App\Controllers\AuthController@login');
     */
    public static function post(string $uri, string $action): void
    {
        // Nettoie l'URI
        $uri = self::cleanUri($uri);
        
        // Stocke la route dans le tableau
        self::$routes['POST'][$uri] = $action;
    }

    /**
     * Nettoie une URI pour éviter les problèmes
     * Exemple : "/login/" devient "login", "/" reste "/"
     */
    private static function cleanUri(string $uri): string
    {
        // Enlève les slashes au début et à la fin
        $uri = trim($uri, '/');
        
        // Si c'est vide, c'est la page d'accueil
        if ($uri === '') {
            return '/';
        }
        
        return $uri;
    }

    /**
     * Trouve et exécute la route correspondante
     * C'est la fonction principale appelée par index.php
     */
    public static function dispatch(string $uri, string $method): void
    {
        // Nettoie l'URI reçue
        $uri = self::cleanUri($uri);

        // Cherche la route correspondante
        $routeFound = self::findRoute($uri, $method);
        
        // Si aucune route trouvée, affiche une erreur 404
        if ($routeFound === null) {
            http_response_code(404);
            echo "404 - Page non trouvée";
            return;
        }

        // Sépare "Controller@method" en deux parties
        // Exemple : "App\Controllers\HomeController@index"
        // Devient : $controllerName = "App\Controllers\HomeController"
        //          $methodName = "index"
        $actionParts = explode('@', $routeFound['action']);
        $controllerName = $actionParts[0];
        $methodName = $actionParts[1];
        
        // Vérifie que la classe du contrôleur existe
        if (!class_exists($controllerName)) {
            echo "Erreur : Le contrôleur $controllerName n'existe pas";
            return;
        }

        // Crée une instance du contrôleur
        $controller = new $controllerName();
        
        // Vérifie que la méthode existe dans le contrôleur
        if (!method_exists($controller, $methodName)) {
            echo "Erreur : La méthode $methodName n'existe pas dans $controllerName";
            return;
        }

        // Récupère les paramètres de l'URL (ex: /topics/5 -> $params = [5])
        $params = $routeFound['params'];
        
        // Convertit les paramètres numériques en entiers
        // Exemple : "5" devient 5 (entier)
        $finalParams = [];
        foreach ($params as $param) {
            if (is_numeric($param)) {
                $finalParams[] = (int) $param;
            } else {
                $finalParams[] = $param;
            }
        }

        // Appelle la méthode du contrôleur avec les paramètres
        // Exemple : $controller->show(5)
        call_user_func_array([$controller, $methodName], $finalParams);
    }

    /**
     * Cherche la route qui correspond à l'URI demandée
     * Retourne null si aucune route n'est trouvée
     */
    private static function findRoute(string $uri, string $method): ?array
    {
        // Vérifie qu'il existe des routes pour cette méthode (GET ou POST)
        if (!isset(self::$routes[$method])) {
            return null;
        }

        // Cherche d'abord une route exacte
        // Exemple : "login" correspond exactement à "login"
        if (isset(self::$routes[$method][$uri])) {
            return [
                'action' => self::$routes[$method][$uri],
                'params' => []
            ];
        }

        // Si pas de route exacte, cherche une route avec paramètres dynamiques
        // Exemple : "topics/5" correspond à "topics/{id}"
        foreach (self::$routes[$method] as $routePattern => $action) {
            // Vérifie si cette route a des paramètres dynamiques (contient {})
            if (strpos($routePattern, '{') !== false) {
                // Convertit le pattern en expression régulière
                $regex = self::patternToRegex($routePattern);
                
                // Vérifie si l'URI correspond au pattern
                if (preg_match($regex, $uri, $matches)) {
                    // Retire le premier élément (correspondance complète)
                    array_shift($matches);
                    
                    // Retourne l'action et les paramètres trouvés
                    return [
                        'action' => $action,
                        'params' => $matches
                    ];
                }
            }
        }

        // Aucune route trouvée
        return null;
    }

    /**
     * Convertit un pattern de route en expression régulière
     * Exemple : "topics/{id}" devient "#^topics/([^/]+)$#"
     */
    private static function patternToRegex(string $pattern): string
    {
        // Nettoie le pattern
        $pattern = self::cleanUri($pattern);
        
        // Remplace {id} par une regex qui capture tout sauf les slashes
        // {id} devient ([^/]+)
        // Exemple : "topics/{id}" devient "topics/([^/]+)"
        $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $pattern);
        
        // Ajoute les délimiteurs de regex (^ = début, $ = fin)
        // Le # au début et à la fin indique que c'est une regex
        return '#^' . $pattern . '$#';
    }
}
