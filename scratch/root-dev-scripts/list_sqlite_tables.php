<?php
$dbPath = 'demo/writable/database.sqlite';
$db = new SQLite3($dbPath);
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
while ($table = $tables->fetchArray(SQLITE3_ASSOC)) {
    echo $table['name'] . "\n";
}
