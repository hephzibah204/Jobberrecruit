<?php
// Quick database connection test
$hostname = '127.0.0.1';
$username = 'root';
$password = 'WaitOnGod2026';
$database = 'jobberrecruit';
$port = 3306;

echo "Testing MySQL connection...\n";
echo "Host: $hostname:$port\n";
echo "User: $username\n";
echo "DB: $database\n\n";

// Try MySQLi connection with timeout
$mysqli = new mysqli($hostname, $username, $password, $database, $port);

if ($mysqli->connect_error) {
    die("❌ Connection failed: " . $mysqli->connect_error . "\n");
}

echo "✅ Connection successful!\n";

// Check tables
$result = $mysqli->query("SHOW TABLES;");
if ($result) {
    echo "✅ Tables found: " . $result->num_rows . "\n";
    while($row = $result->fetch_row()) {
        echo "   - " . $row[0] . "\n";
    }
} else {
    echo "❌ Error querying tables: " . $mysqli->error . "\n";
}

$mysqli->close();
