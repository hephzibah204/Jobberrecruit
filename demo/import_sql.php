<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'jobber_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sqlFile = 'db.sql';
if (!file_exists($sqlFile)) {
    die("SQL file not found: $sqlFile");
}

$sql = file_get_contents($sqlFile);

// Split by semicolon but ignore semicolons inside strings
// Simple split for now, assuming standard SQL file
$queries = explode(';', $sql);

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if ($conn->query($query) === TRUE) {
            // echo "Query executed successfully\n";
        } else {
            echo "Error executing query: " . $conn->error . "\n";
            echo "Query: " . substr($query, 0, 100) . "...\n";
        }
    }
}

$conn->close();
echo "SQL import completed.\n";
