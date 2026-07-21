<?php
$host = '127.0.0.1';
$db   = 'jobberrecruit';
$user = 'root';
$pass = 'WaitOnGod2026';
$port = 3306;

$conn = new mysqli($host, $user, $pass, $db, $port);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = file_get_contents('aptitude_schema.sql');
$queries = explode(';', $sql);

foreach ($queries as $query) {
    $query = trim($query);
    if (!empty($query)) {
        if ($conn->query($query) === TRUE) {
            echo "Successfully executed query.\n";
        } else {
            echo "Error executing query: " . $conn->error . "\n";
        }
    }
}
$conn->close();
echo "Migration complete.\n";
