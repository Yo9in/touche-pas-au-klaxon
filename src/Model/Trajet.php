<?php
namespace App\Model;

use App\Core\Database;
use PDO;

/**
 * Modèle Trajet
 *
 * Gère les opérations de lecture/écriture sur la table "trajet".
 */

class Trajet
{
    /**
     * Retourne les trajets futurs avec des places disponibles,
     * accompagnés des informations d'agence et de conducteur.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getFutursAvecPlaces()
    {
        $pdo = Database::getConnection();

        $sql = "
            SELECT 
                t.id_trajet,
                t.date_heure_depart AS date_depart,
                t.date_heure_arrivee AS date_arrivee,
                t.nb_places_total,
                t.nb_places_disponibles,
                ad.nom AS depart,
                aa.nom AS arrivee,
                u.id_user AS conducteur_id,
                u.prenom AS conducteur_prenom,
                u.nom AS conducteur_nom,
                u.email AS conducteur_email,
                u.telephone AS conducteur_tel
            FROM trajet t
            JOIN agence ad ON ad.id_agence = t.agence_depart_id
            JOIN agence aa ON aa.id_agence = t.agence_arrivee_id
            JOIN utilisateur u ON u.id_user = t.utilisateur_id
            WHERE t.date_heure_depart > NOW()
              AND t.nb_places_disponibles > 0
            ORDER BY t.date_heure_depart ASC
        ";

        return $pdo->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
    /**
     * Crée un nouveau trajet en base.
     *
     * @param array<string, mixed> $data Données du trajet (clés: utilisateur_id, agences, dates, places, commentaire)
     * @return bool true si l'insertion a réussi
     */
    public function create(array $data)
    {
        $pdo = Database::getConnection();

        $sql = "
            INSERT INTO trajet (
                utilisateur_id,
                agence_depart_id,
                agence_arrivee_id,
                date_heure_depart,
                date_heure_arrivee,
                nb_places_total,
                nb_places_disponibles,
                commentaire
            ) VALUES (
                :utilisateur_id,
                :agence_depart_id,
                :agence_arrivee_id,
                :date_heure_depart,
                :date_heure_arrivee,
                :nb_places_total,
                :nb_places_disponibles,
                :commentaire
            )
        ";

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    }
    /**
     * Retourne un trajet par son identifiant.
     *
     * @param int $id Identifiant du trajet
     * @return array<string, mixed>|false Tableau associatif ou false si introuvable
     */
    public function find($id)
    {
        $pdo = Database::getConnection();

        $sql = "
            SELECT 
                t.*,
                ad.nom AS depart_nom,
                aa.nom AS arrivee_nom,
                u.prenom AS conducteur_prenom,
                u.nom AS conducteur_nom,
                u.email AS conducteur_email,
                u.telephone AS conducteur_tel
            FROM trajet t
            JOIN agence ad ON ad.id_agence = t.agence_depart_id
            JOIN agence aa ON aa.id_agence = t.agence_arrivee_id
            JOIN utilisateur u ON u.id_user = t.utilisateur_id
            WHERE t.id_trajet = :id
            LIMIT 1
        ";

        $stmt = $pdo->prepare($sql);
        $stmt->execute(['id' => $id]);

        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    /**
     * Met à jour un trajet existant.
     *
     * @param int $id Identifiant du trajet
     * @param array<string, mixed> $data Données à mettre à jour
     * @return bool true si la mise à jour a réussi
     */
    public function update($id, array $data)
    {
        $pdo = Database::getConnection();

        $sql = "
            UPDATE trajet
            SET agence_depart_id = :agence_depart_id,
                agence_arrivee_id = :agence_arrivee_id,
                date_heure_depart = :date_heure_depart,
                date_heure_arrivee = :date_heure_arrivee,
                nb_places_total = :nb_places_total,
                nb_places_disponibles = :nb_places_disponibles,
                commentaire = :commentaire
            WHERE id_trajet = :id
        ";

        $data['id'] = $id;

        $stmt = $pdo->prepare($sql);
        return $stmt->execute($data);
    }

    /**
     * Supprime un trajet par son identifiant.
     *
     * @param int $id
     * @return bool true si la suppression a réussi
     */
    public function delete($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM trajet WHERE id_trajet = :id");
        return $stmt->execute(['id' => $id]);
    }
}
