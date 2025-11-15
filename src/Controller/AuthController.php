<?php
namespace App\Controller;

use App\Model\Utilisateur;

/**
 * Contrôleur d'authentification.
 *
 * Gère l'affichage du formulaire de connexion, la vérification des identifiants,
 * et la déconnexion de l'utilisateur.
 */
class AuthController
{   
    /**
     * Affiche le formulaire de connexion.
     *
     * @return void
     */
    public function loginForm()
    {
        $title = 'Connexion';
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);

        require __DIR__ . '/../View/login.php';
    }

    /**
     * Traite le formulaire de connexion.
     *
     * Vérifie l'existence de l'utilisateur et la validité du mot de passe,
     * puis stocke l'utilisateur en session.
     *
     * @return void
     */
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

    /**
     * Déconnecte l'utilisateur en supprimant la session.
     *
     * @return void
     */
    public function logout()
    {
        unset($_SESSION['user']);
        header('Location: /');
        exit;
    }
}
