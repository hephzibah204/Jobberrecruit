<?php
$db = new SQLite3('writable/database.sqlite');
$results = $db->query("PRAGMA integrity_check");
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}
