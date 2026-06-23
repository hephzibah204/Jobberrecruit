<?php
$db = new SQLite3(__DIR__ . '/writable/database.sqlite');
$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
while ($table = $tables->fetchArray(SQLITE3_ASSOC)) {
    $tableName = $table['name'];
    $count = $db->querySingle("SELECT COUNT(*) FROM `$tableName`") ?: 0;
    echo "$tableName: $count rows\n";
}
