<?php
namespace App\Controller;

use App\Model\Trajet;
use App\Model\Agence;

class TrajetController
{
    private function requireLogin(): void
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['login_error'] = "Vous devez être connecté pour proposer un trajet.";
            header('Location: /login');
            exit;
        }
    }

    public function create(): void
    {
        $this->requireLogin();

        $agenceModel = new Agence();
        $agences = $agenceModel->all();

        $title = "Proposer un trajet";

        // gestion erreurs + anciennes valeurs
        $errors = $_SESSION['trajet_errors'] ?? [];
        $old    = $_SESSION['trajet_old'] ?? [];
        unset($_SESSION['trajet_errors'], $_SESSION['trajet_old']);

        require __DIR__ . '/../View/trajets/create.php';
    }

    public function store(): void
    {
        $this->requireLogin();

        $user = $_SESSION['user'];

        // Récupération des données du formulaire
        $agence_depart_id   = (int)($_POST['agence_depart_id'] ?? 0);
        $agence_arrivee_id  = (int)($_POST['agence_arrivee_id'] ?? 0);
        $date_depart_raw    = trim($_POST['date_heure_depart'] ?? '');
        $date_arrivee_raw   = trim($_POST['date_heure_arrivee'] ?? '');
        $nb_total           = (int)($_POST['nb_places_total'] ?? 0);
        $nb_dispo           = (int)($_POST['nb_places_disponibles'] ?? 0);
        $commentaire        = trim($_POST['commentaire'] ?? '');

        $errors = [];

        // Convertir datetime-local (yyyy-mm-ddThh:mm) en DATETIME SQL
        $date_depart  = $date_depart_raw  ? str_replace('T', ' ', $date_depart_raw) . ':00' : '';
        $date_arrivee = $date_arrivee_raw ? str_replace('T', ' ', $date_arrivee_raw) . ':00' : '';

        // validations
        if ($agence_depart_id === 0 || $agence_arrivee_id === 0) {
            $errors[] = "Veuillez sélectionner une agence de départ et une agence d'arrivée.";
        }
        if ($agence_depart_id === $agence_arrivee_id) {
            $errors[] = "L'agence de départ doit être différente de l'agence d'arrivée.";
        }
        if ($date_depart_raw === '' || $date_arrivee_raw === '') {
            $errors[] = "Les dates de départ et d'arrivée sont obligatoires.";
        } else {
            if ($date_depart >= $date_arrivee) {
                $errors[] = "La date de départ doit être strictement antérieure à la date d'arrivée.";
            }
            // départ doit être dans le futur
            $now = date('Y-m-d H:i:s');
            if ($date_depart <= $now) {
                $errors[] = "La date de départ doit être dans le futur.";
            }
        }
        if ($nb_total <= 0) {
            $errors[] = "Le nombre total de places doit être strictement positif.";
        }
        if ($nb_dispo < 0 || $nb_dispo > $nb_total) {
            $errors[] = "Le nombre de places disponibles doit être compris entre 0 et le nombre total de places.";
        }

        if (!empty($errors)) {
            $_SESSION['trajet_errors'] = $errors;
            $_SESSION['trajet_old'] = $_POST;
            header('Location: /trajets/create');
            exit;
        }

        $trajetModel = new Trajet();
        $ok = $trajetModel->create([
            'utilisateur_id'       => $user['id'],
            'agence_depart_id'     => $agence_depart_id,
            'agence_arrivee_id'    => $agence_arrivee_id,
            'date_heure_depart'    => $date_depart,
            'date_heure_arrivee'   => $date_arrivee,
            'nb_places_total'      => $nb_total,
            'nb_places_disponibles'=> $nb_dispo,
            'commentaire'          => $commentaire !== '' ? $commentaire : null,
        ]);

        if (!$ok) {
            $_SESSION['trajet_errors'] = ["Une erreur est survenue lors de l'enregistrement du trajet."];
            $_SESSION['trajet_old'] = $_POST;
            header('Location: /trajets/create');
            exit;
        }

        // message flash de succès
        $_SESSION['flash_success'] = "Votre trajet a bien été créé.";
        header('Location: /');
        exit;
    }
}
