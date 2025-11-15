<?php

use PHPUnit\Framework\TestCase;
use App\Core\Database;
use App\Model\Agence;


class AgenceTest extends TestCase
{
    public function testCreateAndDeleteAgence()
    {
        $agenceModel = new Agence();
        $pdo = Database::getConnection();

        $nomTest = 'AgenceTestUnit';

        // 1) Création
        $resultCreate = $agenceModel->create($nomTest);
        $this->assertTrue($resultCreate, "La création d'agence doit retourner true.");

        // 2) Vérifier que l'agence existe
        $stmt = $pdo->prepare("SELECT * FROM agence WHERE nom = :nom");
        $stmt->execute(['nom' => $nomTest]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertNotFalse($row, "L'agence doit exister en base après création.");

        $id = (int)$row['id_agence'];

        // 3) Suppression
        $resultDelete = $agenceModel->delete($id);
        $this->assertTrue($resultDelete, "La suppression d'agence doit retourner true.");

        // 4) Vérifier qu'elle n'existe plus
        $stmt = $pdo->prepare("SELECT * FROM agence WHERE id_agence = :id");
        $stmt->execute(['id' => $id]);
        $rowAfter = $stmt->fetch(PDO::FETCH_ASSOC);

        $this->assertFalse($rowAfter, "L'agence ne doit plus exister après suppression.");
    }
}
