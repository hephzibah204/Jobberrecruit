<?php
try {
    $db = new PDO('sqlite:demo/writable/database.sqlite');
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add manual_certificate to course_certificates
    $db->exec("ALTER TABLE course_certificates ADD COLUMN manual_certificate VARCHAR(255) NULL");
    
    echo "Added manual_certificate column to course_certificates.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
