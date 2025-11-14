<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controller\HomeController;
use App\Controller\AuthController;

// Instancier le router
$router = new Router();

// Route accueil
$router->get('/', 'App\Controller\HomeController@index');

// Authentification
$router->get('/login', 'App\Controller\AuthController@loginForm');
$router->post('/login', 'App\Controller\AuthController@login');
$router->get('/logout', 'App\Controller\AuthController@logout');




// Lancer le router
$router->run();
