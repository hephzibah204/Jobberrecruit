<?php
$db = new SQLite3('writable/database.sqlite');
$results = $db->query("SELECT * FROM plans WHERE plan_type = 'candidate'");
while ($row = $results->fetchArray(SQLITE3_ASSOC)) {
    print_r($row);
}
