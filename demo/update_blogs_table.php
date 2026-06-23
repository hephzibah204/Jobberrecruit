<?php
$db = new SQLite3('writable/database.sqlite');
$db->exec("ALTER TABLE blogs ADD COLUMN tags VARCHAR(255)");
$db->exec("ALTER TABLE blogs ADD COLUMN excerpt TEXT");
$db->exec("ALTER TABLE blogs ADD COLUMN meta_title VARCHAR(255)");
$db->exec("ALTER TABLE blogs ADD COLUMN meta_description TEXT");
$db->exec("ALTER TABLE blogs ADD COLUMN meta_keywords VARCHAR(255)");
$db->exec("ALTER TABLE blogs ADD COLUMN category_id INT");
$db->exec("ALTER TABLE blogs ADD COLUMN is_featured TINYINT DEFAULT 0");
echo "Blogs table updated successfully.";
