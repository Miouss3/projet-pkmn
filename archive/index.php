<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';

use App\Router;

// Configuration des erreurs (à désactiver en production)
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

// Routes publiques
Router::get('/', 'App\Controllers\HomeController@index');

// Routes d'authentification
Router::get('/login', 'App\Controllers\AuthController@showLogin');
Router::post('/login', 'App\Controllers\AuthController@login');
Router::get('/register', 'App\Controllers\AuthController@showRegister');
Router::post('/register', 'App\Controllers\AuthController@register');
Router::get('/logout', 'App\Controllers\AuthController@logout');

// Routes des catégories
Router::get('/categories/{id}', 'App\Controllers\CategoryController@show');
Router::get('/categories/create', 'App\Controllers\CategoryController@create');
Router::post('/categories/store', 'App\Controllers\CategoryController@store');

// Routes des sujets
Router::get('/topics/{id}', 'App\Controllers\TopicController@show');
Router::get('/topics/create', 'App\Controllers\TopicController@create');
Router::get('/topics/create/{categoryId}', 'App\Controllers\TopicController@create');
Router::post('/topics/store', 'App\Controllers\TopicController@store');

// Routes des messages
Router::post('/posts/store', 'App\Controllers\PostController@store');

// Routes d'administration
Router::get('/admin', 'App\Controllers\AdminController@dashboard');

// Routes admin - Sujets
Router::get('/admin/topics', 'App\Controllers\AdminController@topics');
Router::post('/admin/topics/{id}/delete', 'App\Controllers\AdminController@deleteTopic');
Router::post('/admin/topics/{id}/restore', 'App\Controllers\AdminController@restoreTopic');

// Routes admin - Messages
Router::get('/admin/posts', 'App\Controllers\AdminController@posts');
Router::post('/admin/posts/{id}/delete', 'App\Controllers\AdminController@deletePost');
Router::post('/admin/posts/{id}/restore', 'App\Controllers\AdminController@restorePost');

// Routes admin - Catégories
Router::get('/admin/categories', 'App\Controllers\AdminController@categories');
Router::get('/admin/categories/{id}/edit', 'App\Controllers\AdminController@editCategory');
Router::post('/admin/categories/{id}/update', 'App\Controllers\AdminController@updateCategory');
Router::post('/admin/categories/{id}/delete', 'App\Controllers\AdminController@deleteCategory');

// Routes admin - Utilisateurs
Router::get('/admin/users', 'App\Controllers\AdminController@users');
Router::post('/admin/users/{id}/ban', 'App\Controllers\AdminController@banUser');
Router::post('/admin/users/{id}/unban', 'App\Controllers\AdminController@unbanUser');
Router::post('/admin/users/{id}/role', 'App\Controllers\AdminController@updateUserRole');

// Récupération de l'URL
$url = $_GET['url'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'];

// Dispatch de la route
Router::dispatch($url, $method);

