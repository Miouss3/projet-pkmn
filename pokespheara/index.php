<?php
session_start();
require_once __DIR__ . '/vendor/autoload.php';
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);

use App\Router;

// Routes Home
Router::get('/', 'App\Controllers\HomeController@index');
Router::get('home', 'App\Controllers\HomeController@index');

// Routes Users
Router::get('users', 'App\Controllers\UserController@index');
Router::get('users/create', 'App\Controllers\UserController@create');
Router::post('users/store', 'App\Controllers\UserController@store');
Router::get('profile', 'App\Controllers\UserController@profile');

// Routes Authentification
Router::get('login', 'App\Controllers\UserController@login');
Router::post('login', 'App\Controllers\UserController@login');
Router::get('logout', 'App\Controllers\UserController@logout');

// Routes Posts/Topics
Router::get('posts', 'App\Controllers\ForumController@index');
Router::get('posts/create', 'App\Controllers\ForumController@create');
Router::post('posts/store', 'App\Controllers\ForumController@store');
Router::get('posts/show', 'App\Controllers\ForumController@show');
Router::get('posts/edit', 'App\Controllers\ForumController@edit');
Router::post('posts/update', 'App\Controllers\ForumController@update');
Router::post('posts/delete', 'App\Controllers\ForumController@delete');

// Routes Réponses
Router::post('posts/reply', 'App\Controllers\ForumController@reply');

// Routes Catégories
Router::get('categories', 'App\Controllers\CategoryController@index');
Router::get('categories/show', 'App\Controllers\CategoryController@show');

$url = $_GET['url'] ?? '/';
$method = $_SERVER['REQUEST_METHOD'];

Router::dispatch($url, $method);

require_once __DIR__ . '/assets/templates/head.php';
require_once __DIR__ . '/assets/templates/footer.php';