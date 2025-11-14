<?php
namespace App\Controller;

use App\Model\Agence;
use App\Core\Database;
use PDO;

class AdminController
{
    private function requireAdmin()
    {
        if (empty($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
            http_response_code(403);
            echo "Accès interdit. Réservé aux administrateurs.";
            exit;
        }
    }

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
