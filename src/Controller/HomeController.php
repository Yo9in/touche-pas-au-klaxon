<?php
namespace App\Controller;

use App\Model\Trajet;

class HomeController
{
    public function index()
    {
        $trajetModel = new Trajet();
        $trajets = $trajetModel->getFutursAvecPlaces();

        $title = 'Accueil - Touche pas au Klaxon';
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        $flash_success = isset($_SESSION['flash_success']) ? $_SESSION['flash_success'] : null;
        unset($_SESSION['flash_success']);

        require __DIR__ . '/../View/home.php';
    }
}
