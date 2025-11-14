<?php
namespace App\Model;

use App\Core\Database;
use PDO;

class Utilisateur
{
    public function findByEmail($email)
    {
        $pdo = Database::getConnection();

        $sql = "SELECT * FROM utilisateur WHERE email = :email LIMIT 1";
        $stmt = $pdo->prepare($sql);
        $stmt->execute(['email' => $email]);

        return $stmt->fetch(PDO::FETCH_ASSOC); // tableau associatif ou false
    }
}
