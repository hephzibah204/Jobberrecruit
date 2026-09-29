<?php
try {
    $db = new PDO('sqlite:writable/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name='settings'");
    $table = $stmt->fetch();
    if ($table) {
        echo "Settings table exists.\n";
        $stmt = $db->query("PRAGMA table_info(settings)");
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    } else {
        echo "Settings table does NOT exist.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
