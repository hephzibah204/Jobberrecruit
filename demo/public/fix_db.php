<?php

// Load CI4 bootstrap
require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../app/Config/Paths.php';
$paths = new \Config\Paths();
require_once $paths->systemDirectory . '/Boot.php';

use CodeIgniter\Boot;
use Config\Database;

// Initialize the framework
$app = Boot::bootWeb($paths);

$db = Database::connect();

try {
    echo "Attempting to add 'skills' column to 'jobs' table...\n";
    $db->query("ALTER TABLE jobs ADD COLUMN skills TEXT NULL AFTER description");
    echo "Success! 'skills' column added.\n";
} catch (\Exception $e) {
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Note: 'skills' column already exists.\n";
    } else {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

unlink(__FILE__); // Self-destruct for security
