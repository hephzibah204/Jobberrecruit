<?php
try {
    $db = new PDO('sqlite:C:/Users/Abiodun Emmanuel/Documents/CODEBASE/Jobberrecruit/demo/writable/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $db->setAttribute(PDO::ATTR_TIMEOUT, 2); // 2 seconds timeout for busy timeout

    echo "Attempting to begin transaction...\n";
    $db->beginTransaction();
    echo "Transaction begun.\n";

    echo "Attempting to create temp table...\n";
    $db->exec("CREATE TEMP TABLE test_lock (id INTEGER)");
    echo "Temp table created.\n";

    $db->rollBack();
    echo "Transaction rolled back successfully. Database is NOT locked!\n";
} catch (Exception $e) {
    echo "DATABASE IS LOCKED OR ENCOUNTERED ERROR: " . $e->getMessage() . "\n";
}
