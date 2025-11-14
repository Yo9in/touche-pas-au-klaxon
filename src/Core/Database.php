<?php
namespace App\Core;

use PDO;

class Database
{
    private static $pdo = null;

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

        self::$pdo = new PDO($dsn, $user, $pass, [
            PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        ]);

        return self::$pdo;
    }
}
