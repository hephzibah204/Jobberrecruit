<?php
try {
    $db = new PDO('sqlite:writable/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = ['webinars', 'courses'];

    foreach ($tables as $table) {
        echo "--- Columns for {$table} ---\n";
        $stmt = $db->query("PRAGMA table_info({$table})");
        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $col) {
            echo "{$col['name']} ({$col['type']})\n";
        }
        echo "\n";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
