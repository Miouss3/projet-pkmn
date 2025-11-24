<?php

namespace App;

class Router
{
    private static array $routes = [];

    public static function get(string $uri, string $action): void
    {
        self::$routes['GET'][$uri] = $action;
    }

    public static function post(string $uri, string $action): void
    {
        self::$routes['POST'][$uri] = $action;
    }

    public static function dispatch(string $uri, string $method): void
    {
        // Nettoie l'URI
        $uri = trim($uri, '/');
        if (empty($uri)) {
            $uri = '/';
        }

        // Vérifie si la route existe
        if (!isset(self::$routes[$method][$uri])) {
            http_response_code(404);
            echo "404 - Page non trouvée";
            return;
        }

        $action = self::$routes[$method][$uri];
        
        // Parse l'action (Controller@method)
        if (str_contains($action, '@')) {
            [$controllerClass, $method] = explode('@', $action);
            
            if (class_exists($controllerClass)) {
                $controller = new $controllerClass();
                if (method_exists($controller, $method)) {
                    $controller->$method();
                } else {
                    echo "Méthode $method non trouvée dans $controllerClass";
                }
            } else {
                echo "Contrôleur $controllerClass non trouvé";
            }
        }
    }
}