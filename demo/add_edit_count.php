<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'jobber_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "ALTER TABLE jobs ADD COLUMN edit_count INT DEFAULT 0 AFTER views";

if ($conn->query($sql) === TRUE) {
    echo "Column edit_count added successfully.\n";
} else {
    echo "Error: " . $conn->error . "\n";
}

$conn->close();
