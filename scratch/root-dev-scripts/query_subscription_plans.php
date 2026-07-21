<?php
$dbPath = 'demo/writable/database.sqlite';
$db = new SQLite3($dbPath);
$results = $db->query("SELECT * FROM subscription_plans");
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}
