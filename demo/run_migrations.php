<?php
$host = 'localhost';
$user = 'root';
$pass = '';
$db   = 'jobber_db';

$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = file_get_contents('migrations_v2.sql');

// Split SQL by semicolon and filter out empty strings
$queries = array_filter(array_map('trim', explode(';', $sql)));

foreach ($queries as $query) {
    if (empty($query)) continue;
    if ($conn->query($query) === TRUE) {
        echo "Query executed successfully: " . substr($query, 0, 50) . "...\n";
    } else {
        echo "Error: " . $conn->error . "\nQuery: " . $query . "\n";
    }
}

$conn->close();
echo "Migration completed.\n";
