<?php
try {
    $db = new PDO('sqlite:writable/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $tables = ['webinars', 'courses', 'course_enrollments', 'affiliate_settings', 'job_reports'];

    foreach ($tables as $table) {
        echo "--- {$table} ---\n";
        try {
            $stmt = $db->query("SELECT * FROM {$table}");
            print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
        } catch (Exception $e) {
            echo "Error checking table {$table}: " . $e->getMessage() . "\n";
        }
        echo "\n";
    }

} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
}
