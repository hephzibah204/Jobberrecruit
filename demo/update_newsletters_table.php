<?php
$db = new SQLite3('writable/database.sqlite');

$db->exec("ALTER TABLE newsletters ADD COLUMN sent_count INTEGER DEFAULT 0");
$db->exec("ALTER TABLE newsletters ADD COLUMN open_count INTEGER DEFAULT 0");
$db->exec("ALTER TABLE newsletters ADD COLUMN click_count INTEGER DEFAULT 0");
$db->exec("ALTER TABLE newsletters ADD COLUMN target_industries TEXT DEFAULT NULL");
$db->exec("ALTER TABLE newsletters ADD COLUMN scheduled_at DATETIME DEFAULT NULL");
$db->exec("ALTER TABLE newsletters ADD COLUMN updated_at DATETIME DEFAULT CURRENT_TIMESTAMP");

echo "Newsletters table updated successfully.";
