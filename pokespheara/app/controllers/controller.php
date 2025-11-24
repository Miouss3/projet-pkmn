<?php
namespace App\Controllers;

class Controller
{
    public function view($view, $data = [])
    {
        extract($data);
        require __DIR__ . '/../Views/' . $view . '.php';
    }
}
