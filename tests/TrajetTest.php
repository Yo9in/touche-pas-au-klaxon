<?php

use PHPUnit\Framework\TestCase;
use App\Core\Database;
use App\Model\Trajet;


class TrajetTest extends TestCase
{
    public function testCreateTrajet()
    {
        $pdo = Database::getConnection();
        $trajetModel = new Trajet();

        // Récupérer un utilisateur + 2 agences existants
        $userId = (int)$pdo->query("SELECT id_user FROM utilisateur LIMIT 1")->fetchColumn();
        $agences = $pdo->query("SELECT id_agence FROM agence LIMIT 2")->fetchAll(PDO::FETCH_COLUMN);

        $this->assertGreaterThan(0, $userId, "Il doit exister au moins un utilisateur.");
        $this->assertGreaterThanOrEqual(2, count($agences), "Il doit exister au moins deux agences.");

        $now = new DateTime();
        $depart = clone $now;
        $depart->modify('+1 day');
        $arrivee = clone $depart;
        $arrivee->modify('+3 hours');

        $data = [
            'utilisateur_id'        => $userId,
            'agence_depart_id'      => (int)$agences[0],
            'agence_arrivee_id'     => (int)$agences[1],
            'date_heure_depart'     => $depart->format('Y-m-d H:i:s'),
            'date_heure_arrivee'    => $arrivee->format('Y-m-d H:i:s'),
            'nb_places_total'       => 4,
            'nb_places_disponibles' => 3,
            'commentaire'           => 'Trajet de test unitaire',
        ];

        // 1) Création
        $result = $trajetModel->create($data);
        $this->assertTrue($result, "La création de trajet doit retourner true.");

        // 2) Vérifier qu'il existe en base
        $stmt = $pdo->prepare("
            SELECT * FROM trajet
            WHERE utilisateur_id = :user
              AND agence_depart_id = :ad
              AND agence_arrivee_id = :aa
              AND commentaire = :commentaire
            ORDER BY id_trajet DESC
            LIMIT 1
        ");
        $stmt->execute([
            'user'        => $data['utilisateur_id'],
            'ad'          => $data['agence_depart_id'],
            'aa'          => $data['agence_arrivee_id'],
            'commentaire' => $data['commentaire'],
        ]);

        $created = $stmt->fetch(PDO::FETCH_ASSOC);
        $this->assertNotFalse($created, "Le trajet doit exister après création.");

        // 3) Nettoyage : supprimer le trajet de test
        if ($created) {
            $del = $pdo->prepare("DELETE FROM trajet WHERE id_trajet = :id");
            $del->execute(['id' => $created['id_trajet']]);
        }
    }
}
