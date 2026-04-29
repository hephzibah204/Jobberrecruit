<?php
require 'vendor/autoload.php';
define('FCPATH', __DIR__ . '/public/');
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
$app = \CodeIgniter\Boot::bootByCommandLine($paths);

$db = \Config\Database::connect();
$tables = $db->listTables();
foreach ($tables as $table) {
    echo $table . "\n";
}

