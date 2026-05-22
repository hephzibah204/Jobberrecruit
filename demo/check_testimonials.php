<?php
try {
    $db = new PDO('sqlite:writable/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $table = 'testimonials';
    echo "--- Table structure for {$table} ---\n";
    $stmt = $db->query("PRAGMA table_info({$table})");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    print_r($columns);

    echo "\n--- Sample Data ---\n";
    $stmt2 = $db->query("SELECT * FROM {$table} LIMIT 2");
    $data = $stmt2->fetchAll(PDO::FETCH_ASSOC);
    print_r($data);

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
