<?php

// Settings
$host = 'localhost';
$user = 'bit_academy';
$pass = 'bit_academy';
$db = 'Eindproject';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    // Connecting to the database
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (PDOException $e) {
    // Error message if the connection fails
    echo "Connection failed: " . $e->getMessage();
}

?>
