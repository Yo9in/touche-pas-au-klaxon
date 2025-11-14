<?php
namespace App\Model;

use App\Core\Database;

class Trajet
{
    public function getFutursAvecPlaces(): array
    {
        $pdo = Database::getConnection();

        $sql = "
            SELECT 
                t.date_heure_depart AS date_depart,
                ad.nom AS depart,
                aa.nom AS arrivee,
                t.nb_places_disponibles
            FROM trajet t
            JOIN agence ad ON ad.id_agence = t.agence_depart_id
            JOIN agence aa ON aa.id_agence = t.agence_arrivee_id
            WHERE t.date_heure_depart > NOW()
              AND t.nb_places_disponibles > 0
            ORDER BY t.date_heure_depart ASC
        ";

        return $pdo->query($sql)->fetchAll();
    }
}
