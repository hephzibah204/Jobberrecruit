<?php
try {
    $dsn = "mysql:host=127.0.0.1;port=3306;dbname=jobber_db";
    $user = "root";
    $pass = "";
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_TIMEOUT => 2,
    ];
    $pdo = new PDO($dsn, $user, $pass, $options);
    echo "Connected successfully with PDO\n";
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage() . "\n";
}
