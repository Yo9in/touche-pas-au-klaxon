<?php
namespace App\Controller;

use App\Model\Trajet;

class HomeController
{
    public function index(): void
    {
        $trajetModel = new Trajet();
        $trajets = $trajetModel->getFutursAvecPlaces();

        $title = 'Accueil - Touche pas au Klaxon';
        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;

        require __DIR__ . '/../View/home.php';
    }
}
