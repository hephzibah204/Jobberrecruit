<?php
$db = new SQLite3('writable/database.sqlite');
$db->exec("ALTER TABLE jobs ADD COLUMN edit_count INTEGER DEFAULT 0");
echo "Column edit_count added to jobs table.\n";
