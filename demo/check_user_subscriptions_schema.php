<?php
try {
    $db = new PDO('sqlite:C:/Users/Abiodun Emmanuel/Documents/CODEBASE/Jobberrecruit/demo/writable/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // List all tables
    $stmt = $db->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "TABLES:\n" . implode(", ", $tables) . "\n\n";

    // check plans schema
    if (in_array('plans', $tables)) {
        $stmt = $db->query("PRAGMA table_info(plans)");
        echo "COLUMNS OF plans:\n";
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    } else {
        echo "No plans table found!\n";
    }

    // check subscription_plans schema
    if (in_array('subscription_plans', $tables)) {
        $stmt = $db->query("PRAGMA table_info(subscription_plans)");
        echo "COLUMNS OF subscription_plans:\n";
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    } else {
        echo "No subscription_plans table found!\n";
    }

} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
