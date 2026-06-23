<?php
// Initialize CodeIgniter manually without routing
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$db = \Config\Database::connect();
$plans = $db->table('plans')->get()->getResultArray();
foreach ($plans as $p) {
    echo $p['id'] . ' - ' . $p['name'] . ' - Type: ' . $p['plan_type'] . ' - Credits: ' . $p['monthly_job_credits'] . "\n";
}
