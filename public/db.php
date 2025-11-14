<?php
function db(): PDO {
    static $pdo = null;
    if ($pdo) return $pdo;

    // ⚠️ MAMP par défaut : port 8889, login root / root
    $host = '127.0.0.1';
    $port = 8889;
    $db   = 'covoiturage';
    $user = 'root';
    $pass = 'root';
    $charset = 'utf8mb4';

    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=$charset";
    $pdo = new PDO($dsn, $user, $pass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
    return $pdo;
}
