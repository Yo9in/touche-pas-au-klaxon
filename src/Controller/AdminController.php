<?php
namespace App\Controller;

use App\Model\Agence;
use App\Core\Database;
use PDO;

/**
 * Contrôleur de la partie administrateur.
 *
 * Permet d'accéder au tableau de bord admin et de gérer les agences.
 */
class AdminController
{
     /**
     * Vérifie que l'utilisateur connecté possède le rôle admin.
     *
     * @return void
     */
    private function requireAdmin()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo "Accès interdit. Réservé aux administrateurs.";
            exit;
        }
    }

    /**
     * Affiche le tableau de bord administrateur.
     *
     * Statistiques : nombre d'utilisateurs, d'agences, de trajets.
     *
     * @return void
     */
    public function index()
    {
        $this->requireAdmin();

        $title = "Tableau de bord admin";
        $user = $_SESSION['user'];

        // Quelques stats simples
        $pdo = Database::getConnection();

        $nbUsers = $pdo->query("SELECT COUNT(*) AS c FROM utilisateur")->fetch(PDO::FETCH_ASSOC)['c'];
        $nbAgences = $pdo->query("SELECT COUNT(*) AS c FROM agence")->fetch(PDO::FETCH_ASSOC)['c'];
        $nbTrajets = $pdo->query("SELECT COUNT(*) AS c FROM trajet")->fetch(PDO::FETCH_ASSOC)['c'];

        require __DIR__ . '/../View/admin/index.php';
    }

    /**
     * Affiche la liste des agences + formulaire d'ajout.
     *
     * @return void
     */
    public function agences()
    {
        $this->requireAdmin();

        $agenceModel = new Agence();
        $agences = $agenceModel->all();

        $title = "Gestion des agences";
        $user = $_SESSION['user'];

        $error  = isset($_SESSION['admin_agence_error']) ? $_SESSION['admin_agence_error'] : null;
        $success = isset($_SESSION['admin_agence_success']) ? $_SESSION['admin_agence_success'] : null;
        unset($_SESSION['admin_agence_error'], $_SESSION['admin_agence_success']);

        require __DIR__ . '/../View/admin/agences.php';
    }

    /**
     * Enregistre une nouvelle agence.
     *
     * @return void
     */
    public function storeAgence()
    {
        $this->requireAdmin();

        $nom = isset($_POST['nom']) ? trim($_POST['nom']) : '';

        if ($nom === '') {
            $_SESSION['admin_agence_error'] = "Le nom de l'agence est obligatoire.";
            header('Location: /admin/agences');
            exit;
        }

        $agenceModel = new Agence();
        $ok = $agenceModel->create($nom);

        if ($ok) {
            $_SESSION['admin_agence_success'] = "Agence ajoutée avec succès.";
        } else {
            $_SESSION['admin_agence_error'] = "Erreur lors de l'ajout de l'agence.";
        }

        header('Location: /admin/agences');
        exit;
    }

    /**
     * Supprime une agence par son identifiant.
     *
     * @return void
     */
    public function deleteAgence()
    {
        $this->requireAdmin();

        $id = isset($_POST['id_agence']) ? (int)$_POST['id_agence'] : 0;
        if ($id <= 0) {
            $_SESSION['admin_agence_error'] = "Id d'agence invalide.";
            header('Location: /admin/agences');
            exit;
        }

        $agenceModel = new Agence();
        $ok = $agenceModel->delete($id);

        if ($ok) {
            $_SESSION['admin_agence_success'] = "Agence supprimée.";
        } else {
            $_SESSION['admin_agence_error'] = "Impossible de supprimer cette agence (peut-être utilisée dans un trajet).";
        }

        header('Location: /admin/agences');
        exit;
    }
}
