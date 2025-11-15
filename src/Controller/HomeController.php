<?php
namespace App\Controller;

use App\Model\Trajet;

/**
 * Contrôleur de la page d'accueil.
 *
 * Affiche la liste des trajets à venir avec places disponibles.
 * Récupère les données via le modèle Trajet et les passe à la vue home.php.
 */

class HomeController
{
    /**
     * Affiche la page d'accueil avec la liste des trajets futurs.
     *
     * @return void
     */
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
