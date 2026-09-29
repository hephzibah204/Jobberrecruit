<?php
$dbPath = 'demo/writable/database.sqlite';
if (!file_exists($dbPath)) {
    die("Database not found at $dbPath\n");
}
$db = new SQLite3($dbPath);
$results = $db->query("SELECT id, name, code, plan_type, base_price, is_active FROM plans");
if (!$results) {
    die("Query failed: " . $db->lastErrorMsg() . "\n");
}
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}
