<?php
// db.php - Database configuratie (MySQL via Plesk)

require_once __DIR__ . '/config.php';

try {
    $dsn = 'mysql:host=' . DB_HOST . ';dbname=' . DB_NAME . ';charset=' . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Databaseverbinding mislukt: ' . htmlspecialchars($e->getMessage()));
}

// Tabel aanmaken als die nog niet bestaat
$pdo->exec("
    CREATE TABLE IF NOT EXISTS posts (
        id INT AUTO_INCREMENT PRIMARY KEY,
        titel VARCHAR(255) NOT NULL,
        tekst TEXT NOT NULL,
        datum DATE NOT NULL,
        locatie VARCHAR(255),
        afbeelding VARCHAR(255),
        aangemaakt_op TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4
");
