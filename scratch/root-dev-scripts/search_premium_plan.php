<?php
$dbPath = 'demo/writable/database.sqlite';
$db = new SQLite3($dbPath);
$results = $db->query("SELECT * FROM plans WHERE name LIKE '%Premium%' OR code LIKE '%premium%'");
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}
