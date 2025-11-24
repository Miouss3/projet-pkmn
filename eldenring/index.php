<?php
require_once __DIR__ . '/app/controller/EldenController.php';
$controller = new EldenController();

$page = $_GET['page'] ?? 'personnage';

switch ($page) {
    case 'personnage':
        $controller->personnage();
        break;

    case 'boss':
        $controller->boss();
        break;

    case 'combat':
        $controller->combat();
        break;

    default:
        echo "Page non trouvée sal nul !";
        break;
}

