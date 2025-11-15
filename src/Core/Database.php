<?php
namespace App\Core;

use PDO;
use PDOException;

/**
 * Classe Database
 *
 * Fournit une connexion PDO unique à la base de données.
 * Implémente un simple pattern Singleton pour éviter
 * de multiples connexions lors du cycle de requête.
 */
class Database
{
   /**
     * Instance PDO unique.
     *
     * @var PDO|null
     */
    private static $pdo = null;

    /**
     * Retourne une connexion PDO fonctionnelle.
     *
     * @return PDO
     */
    public static function getConnection()
    {
        if (self::$pdo !== null) {
            return self::$pdo;
        }

        $host = '127.0.0.1';
        $port = 8889;
        $db   = 'covoiturage';
        $user = 'root';
        $pass = 'root';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";

        try {
            self::$pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]);
        } catch (PDOException $e) {
            die('Erreur connexion BDD : ' . $e->getMessage());
        }

        return self::$pdo;
    }
}
