<?php
$db = new SQLite3('writable/database.sqlite');
$results = $db->query("PRAGMA table_info(job_seekers)");
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}
