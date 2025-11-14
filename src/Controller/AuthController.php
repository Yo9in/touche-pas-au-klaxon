<?php
namespace App\Controller;

use App\Model\Utilisateur;

class AuthController
{
    public function loginForm()
    {
        $title = 'Connexion';
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require __DIR__ . '/../View/login.php';
    }

    public function login()
    {
        // Récupérer les données POST
        $email = isset($_POST['email']) ? trim($_POST['email']) : '';
        $password = isset($_POST['password']) ? $_POST['password'] : '';

        if ($email === '' || $password === '') {
            $_SESSION['login_error'] = "Veuillez remplir les deux champs.";
            header('Location: /login');
            exit;
        }

        $userModel = new Utilisateur();
        $user = $userModel->findByEmail($email);

        // ⚠️ Pour l’instant on compare en clair avec 'changeme'
        if (!$user || $user['mot_de_passe'] !== $password) {
            $_SESSION['login_error'] = "Identifiants incorrects.";
            header('Location: /login');
            exit;
        }

        // Stocker les infos utiles en session
        $_SESSION['user'] = [
            'id'     => $user['id_user'],
            'prenom' => $user['prenom'],
            'nom'    => $user['nom'],
            'role'   => $user['role'],
            'email'  => $user['email'],
        ];

        header('Location: /');
        exit;
    }

    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: /');
        exit;
    }
}
