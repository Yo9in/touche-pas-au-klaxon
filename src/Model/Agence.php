<?php
namespace App\Model;


use App\Core\Database;
use PDO;

class Agence
{
    public function all()
    {
        $pdo = Database::getConnection();
        $sql = "SELECT id_agence, nom FROM agence ORDER BY nom ASC";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create($nom)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO agence (nom) VALUES (:nom)");
        return $stmt->execute(['nom' => $nom]);
    }

    public function delete($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM agence WHERE id_agence = :id");
        return $stmt->execute(['id' => $id]);
    }
}
