<?php
namespace App\Model;


use App\Core\Database;
use PDO;

/**
 * Modèle Agence
 *
 * Gère les opérations CRUD sur la table "agence".
 */

class Agence
{
    /**
     * Retourne toutes les agences triées par nom.
     *
     * @return array<int, array<string, mixed>>
     */
    public function all()
    {
        $pdo = Database::getConnection();
        $sql = "SELECT id_agence, nom FROM agence ORDER BY nom ASC";
        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Crée une nouvelle agence en base.
     *
     * @param string $nom Nom de l'agence
     * @return bool true si la création a réussi
     */
    public function create($nom)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("INSERT INTO agence (nom) VALUES (:nom)");
        return $stmt->execute(['nom' => $nom]);
    }

    /**
     * Supprime une agence par son identifiant.
     *
     * @param int $id
     * @return bool true si la suppression a réussi
     */
    public function delete($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM agence WHERE id_agence = :id");
        return $stmt->execute(['id' => $id]);
    }
}
