<?php
$dbPath = 'demo/writable/database.sqlite';
$db = new SQLite3($dbPath);
$results = $db->query("SELECT * FROM plans WHERE plan_type = 'candidate'");
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    echo "ID: " . $row['id'] . "\n";
    echo "Name: " . $row['name'] . "\n";
    echo "Code: " . $row['code'] . "\n";
    echo "Features: " . $row['features'] . "\n";
    echo "Is Active: " . $row['is_active'] . "\n";
    echo "-------------------\n";
}
