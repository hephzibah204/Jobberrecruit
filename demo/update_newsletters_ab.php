<?php
$db = new SQLite3('writable/database.sqlite');

// 1. Add A/B testing support to newsletters table
$db->exec("ALTER TABLE newsletters ADD COLUMN subject_b VARCHAR(255) DEFAULT NULL");
$db->exec("ALTER TABLE newsletters ADD COLUMN content_b TEXT DEFAULT NULL");
$db->exec("ALTER TABLE newsletters ADD COLUMN test_split_percent INTEGER DEFAULT 0"); // 0 means no A/B test
$db->exec("ALTER TABLE newsletters ADD COLUMN test_status VARCHAR(50) DEFAULT 'none'"); // none, running, finished
$db->exec("ALTER TABLE newsletters ADD COLUMN winning_variation VARCHAR(1) DEFAULT NULL"); // 'A' or 'B'
$db->exec("ALTER TABLE newsletters ADD COLUMN open_count_b INTEGER DEFAULT 0");
$db->exec("ALTER TABLE newsletters ADD COLUMN click_count_b INTEGER DEFAULT 0");

echo "Newsletters table updated for A/B testing.";
