<?php
// Include CodeIgniter environment
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$db = \Config\Database::connect();

echo "<pre>";
echo "Testing Employer with user_id = 44...\n\n";

// 1. Check Employer Table Schema
echo "--- Employers Table Schema ---\n";
$fields = $db->getFieldData('employers');
foreach ($fields as $field) {
    echo "{$field->name} ({$field->type})\n";
}
echo "\n";

// 2. Fetch Employer with user_id = 44
echo "--- Row in Employers Table for user_id = 44 ---\n";
$query = $db->table('employers')->where('user_id', 44)->get();
$row = $query->getRowArray();

if ($row) {
    print_r($row);
} else {
    echo "NO ROW FOUND for user_id = 44\n";
}

// 3. Fetch Job Seeker with id = 95
echo "\n--- Row in Job Seekers Table for id = 95 ---\n";
$query = $db->table('job_seekers')->where('id', 95)->get();
$row = $query->getRowArray();

if ($row) {
    print_r($row);
} else {
    echo "NO ROW FOUND for id = 95\n";
}

echo "</pre>";
