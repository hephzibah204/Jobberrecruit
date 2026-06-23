<?php
$db = \Config\Database::connect();
try {
    $db->query("ALTER TABLE jobs ADD COLUMN edit_count INT DEFAULT 0");
    echo "Column edit_count added successfully.\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
