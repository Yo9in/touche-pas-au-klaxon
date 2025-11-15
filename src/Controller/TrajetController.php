<?php
namespace App\Controller;

use App\Model\Trajet;
use App\Model\Agence;

/**
 * Contrôleur des trajets.
 *
 * Gère l'affichage des trajets, leur création, modification,
 * affichage détaillé et suppression.
 */

class TrajetController
{
    /**
     * Vérifie que l'utilisateur est connecté.
     *
     * Redirige vers /login si ce n'est pas le cas.
     *
     * @return void
     */
    private function requireLogin(): void
    {
        if (empty($_SESSION['user'])) {
            $_SESSION['login_error'] = "Vous devez être connecté pour proposer un trajet.";
            header('Location: /login');
            exit;
        }
    }
    /**
     * Affiche le formulaire de création de trajet.
     *
     * @return void
     */
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

    /**
     * Enregistre un nouveau trajet en base après validation des données.
     *
     * @return void
     */
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
        /**
     * Affiche les détails d'un trajet.
     *
     * @return void
     */
    public function show(): void
    {
        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            http_response_code(400);
            echo "Id trajet invalide.";
            return;
        }

        $trajetModel = new \App\Model\Trajet();
        $trajet = $trajetModel->find($id);

        if (!$trajet) {
            http_response_code(404);
            echo "Trajet introuvable.";
            return;
        }

        $user = isset($_SESSION['user']) ? $_SESSION['user'] : null;
        $title = "Détail du trajet";

        require __DIR__ . '/../View/trajets/show.php';
    }
        /**
     * Affiche le formulaire d'édition d'un trajet existant.
     *
     * @return void
     */
    public function edit()
    {
        $this->requireLogin();

        $id = isset($_GET['id']) ? (int)$_GET['id'] : 0;
        if ($id <= 0) {
            echo "Id trajet invalide.";
            return;
        }

        $trajetModel = new \App\Model\Trajet();
        $trajet = $trajetModel->find($id);

        if (!$trajet) {
            echo "Trajet introuvable.";
            return;
        }

        // Vérifier que l'utilisateur courant est le propriétaire
        $user = $_SESSION['user'];
        if ((int)$trajet['utilisateur_id'] !== (int)$user['id']) {
            http_response_code(403);
            echo "Vous n'êtes pas autorisé à modifier ce trajet.";
            return;
        }

        $agenceModel = new \App\Model\Agence();
        $agences = $agenceModel->all();

        $title = "Modifier le trajet";

        $errors = isset($_SESSION['trajet_errors']) ? $_SESSION['trajet_errors'] : [];
        $old    = isset($_SESSION['trajet_old']) ? $_SESSION['trajet_old'] : [];
        unset($_SESSION['trajet_errors'], $_SESSION['trajet_old']);

        // Si pas d'anciennes valeurs, on pré-remplit avec les données BDD
        if (empty($old)) {
            $old = [
                'id_trajet'             => $trajet['id_trajet'],
                'agence_depart_id'      => $trajet['agence_depart_id'],
                'agence_arrivee_id'     => $trajet['agence_arrivee_id'],
                'date_heure_depart'     => str_replace(' ', 'T', substr($trajet['date_heure_depart'], 0, 16)),
                'date_heure_arrivee'    => str_replace(' ', 'T', substr($trajet['date_heure_arrivee'], 0, 16)),
                'nb_places_total'       => $trajet['nb_places_total'],
                'nb_places_disponibles' => $trajet['nb_places_disponibles'],
                'commentaire'           => $trajet['commentaire'],
            ];
        }

        require __DIR__ . '/../View/trajets/edit.php';
    }

    /**
     * Met à jour un trajet existant après validation.
     *
     * @return void
     */
    public function update()
    {
        $this->requireLogin();
        $user = $_SESSION['user'];

        $id = isset($_POST['id_trajet']) ? (int)$_POST['id_trajet'] : 0;
        if ($id <= 0) {
            echo "Id trajet invalide.";
            return;
        }

        $trajetModel = new \App\Model\Trajet();
        $trajet = $trajetModel->find($id);

        if (!$trajet) {
            echo "Trajet introuvable.";
            return;
        }

        // Vérifier que l'utilisateur courant est le propriétaire
        if ((int)$trajet['utilisateur_id'] !== (int)$user['id']) {
            http_response_code(403);
            echo "Vous n'êtes pas autorisé à modifier ce trajet.";
            return;
        }

        // Récupération des données du formulaire
        $agence_depart_id   = (int)($_POST['agence_depart_id'] ?? 0);
        $agence_arrivee_id  = (int)($_POST['agence_arrivee_id'] ?? 0);
        $date_depart_raw    = trim($_POST['date_heure_depart'] ?? '');
        $date_arrivee_raw   = trim($_POST['date_heure_arrivee'] ?? '');
        $nb_total           = (int)($_POST['nb_places_total'] ?? 0);
        $nb_dispo           = (int)($_POST['nb_places_disponibles'] ?? 0);
        $commentaire        = trim($_POST['commentaire'] ?? '');

        $errors = [];

        $date_depart  = $date_depart_raw  ? str_replace('T', ' ', $date_depart_raw) . ':00' : '';
        $date_arrivee = $date_arrivee_raw ? str_replace('T', ' ', $date_arrivee_raw) . ':00' : '';

        // mêmes validations que pour store()
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
            header('Location: /trajets/edit?id=' . $id);
            exit;
        }

        $ok = $trajetModel->update($id, [
            'agence_depart_id'      => $agence_depart_id,
            'agence_arrivee_id'     => $agence_arrivee_id,
            'date_heure_depart'     => $date_depart,
            'date_heure_arrivee'    => $date_arrivee,
            'nb_places_total'       => $nb_total,
            'nb_places_disponibles' => $nb_dispo,
            'commentaire'           => $commentaire !== '' ? $commentaire : null,
        ]);

        if (!$ok) {
            $_SESSION['trajet_errors'] = ["Une erreur est survenue lors de la mise à jour du trajet."];
            $_SESSION['trajet_old'] = $_POST;
            header('Location: /trajets/edit?id=' . $id);
            exit;
        }

        $_SESSION['flash_success'] = "Votre trajet a bien été mis à jour.";
        header('Location: /');
        exit;
    }

    /**
     * Supprime un trajet appartenant à l'utilisateur connecté.
     *
     * @return void
     */
    public function delete()
    {
        $this->requireLogin();
        $user = $_SESSION['user'];

        $id = isset($_POST['id_trajet']) ? (int)$_POST['id_trajet'] : 0;
        if ($id <= 0) {
            echo "Id trajet invalide.";
            return;
        }

        $trajetModel = new \App\Model\Trajet();
        $trajet = $trajetModel->find($id);

        if (!$trajet) {
            echo "Trajet introuvable.";
            return;
        }

        if ((int)$trajet['utilisateur_id'] !== (int)$user['id']) {
            http_response_code(403);
            echo "Vous n'êtes pas autorisé à supprimer ce trajet.";
            return;
        }

        $trajetModel->delete($id);

        $_SESSION['flash_success'] = "Votre trajet a bien été supprimé.";
        header('Location: /');
        exit;
    }


}
