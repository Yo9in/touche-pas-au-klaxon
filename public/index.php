<?php
session_start();

error_reporting(E_ALL);
ini_set('display_errors', '1');

require __DIR__ . '/../vendor/autoload.php';

use App\Core\Router;
use App\Controller\HomeController;
use App\Controller\AuthController;
use App\Controller\AdminController;


// Instancier le router
$router = new Router();

// Route accueil
$router->get('/', 'App\Controller\HomeController@index');

// Authentification
$router->get('/login', 'App\Controller\AuthController@loginForm');
$router->post('/login', 'App\Controller\AuthController@login');
$router->get('/logout', 'App\Controller\AuthController@logout');

// trajets
$router->get('/trajets/show', 'App\Controller\TrajetController@show');
$router->get('/trajets/create', 'App\Controller\TrajetController@create');
$router->post('/trajets', 'App\Controller\TrajetController@store');
$router->get('/trajets/edit', 'App\Controller\TrajetController@edit');
$router->post('/trajets/update', 'App\Controller\TrajetController@update');
$router->post('/trajets/delete', 'App\Controller\TrajetController@delete');

// admin
$router->get('/admin', 'App\Controller\AdminController@index');
$router->get('/admin/agences', 'App\Controller\AdminController@agences');
$router->post('/admin/agences/store', 'App\Controller\AdminController@storeAgence');
$router->post('/admin/agences/delete', 'App\Controller\AdminController@deleteAgence');



// Lancer le router
$router->run();
