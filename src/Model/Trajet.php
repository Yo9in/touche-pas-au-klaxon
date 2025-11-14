<?php
namespace App\Model;

use App\Core\Database;
use PDO;

class Trajet
{
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

    public function delete($id)
    {
        $pdo = Database::getConnection();
        $stmt = $pdo->prepare("DELETE FROM trajet WHERE id_trajet = :id");
        return $stmt->execute(['id' => $id]);
    }
}
