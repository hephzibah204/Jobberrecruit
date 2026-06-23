<?php
$db = new SQLite3('writable/database.sqlite');
$results = $db->query("PRAGMA table_info(messages)");
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}
