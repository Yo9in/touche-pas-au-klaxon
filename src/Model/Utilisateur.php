<?php
namespace App\Model;

use App\Core\Database;
use PDO;

/**
 * Modèle Utilisateur
 *
 * Gère l'accès à la table "utilisateur".
 */
class Utilisateur
{
    /**
     * Recherche un utilisateur par son email.
     *
     * @param string $email Adresse email à rechercher
     * @return array<string, mixed>|false Données de l'utilisateur ou false si introuvable
     */
    public function findByEmail($email)
    {
        $pdo = Database::getConnection();

        $sql = "SELECT * FROM utilisateur WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC); // tableau associatif ou false
    }
}
