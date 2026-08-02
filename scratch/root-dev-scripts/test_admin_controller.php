<?php
require 'app/Config/Paths.php';
$paths = new \Config\Paths();
require 'vendor/codeigniter4/framework/system/Boot.php';
CodeIgniter\Boot::bootWeb($paths);

try {
    $controller = new \App\Controllers\AdminUserController();
    echo "Controller instantiated successfully.\n";
} catch (\Throwable $e) {
    echo "Error: " . $e->getMessage() . "\n" . $e->getTraceAsString();
}
