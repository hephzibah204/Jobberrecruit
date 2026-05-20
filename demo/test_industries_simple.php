<?php
// Simple test to check industries
$host = 'localhost';
$db   = 'jobberrecruit';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
     echo "Connected successfully. ";
     
     $stmt = $pdo->query('SELECT COUNT(*) as total FROM industries');
     $row = $stmt->fetch();
     echo "Total industries: " . $row['total'] . "\n";
     
     if ($row['total'] > 0) {
         $stmt = $pdo->query('SELECT id, name, slug FROM industries LIMIT 10');
         $industries = $stmt->fetchAll();
         foreach ($industries as $ind) {
             echo "ID: {$ind['id']}, Name: {$ind['name']}, Slug: {$ind['slug']}\n";
         }
     } else {
         echo "No industries found in database.\n";
     }
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}