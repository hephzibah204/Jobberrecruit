<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'jobber_db';

try {
    $conn = new mysqli($host, $user, $pass);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    $sql = "CREATE DATABASE IF NOT EXISTS $db";
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully or already exists\n";
    } else {
        echo "Error creating database: " . $conn->error . "\n";
    }

    $conn->close();
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
