<?php
// Standalone Database Diagnostic Script
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
require FCPATH . '../app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . DIRECTORY_SEPARATOR . 'bootstrap.php';

$db = \Config\Database::connect();
echo "<pre>";
echo "<h2>Database Diagnosis</h2>\n";

// 1. Get logged in user from session if possible
$session = \Config\Services::session();
$userId = $session->get('logged_in');
echo "Current Session User ID: " . print_r($userId, true) . "\n\n";

// 2. Fetch all employers
echo "--- ALL EMPLOYERS IN DATABASE ---\n";
$query = $db->query("SELECT id, user_id, company_name FROM employers");
$employers = $query->getResultArray();
if (empty($employers)) {
    echo "NO EMPLOYERS FOUND IN TABLE!\n";
} else {
    foreach ($employers as $emp) {
        echo "Employer ID: {$emp['id']} | User ID: {$emp['user_id']} | Company: {$emp['company_name']}\n";
    }
}
echo "\n";

// 3. Look up Employer by User ID = 44 and ID = 44
echo "--- Lookup Employer id = 44 ---\n";
$query = $db->query("SELECT * FROM employers WHERE id = 44");
print_r($query->getRowArray() ?: "Not found");
echo "\n";

echo "--- Lookup Employer user_id = 44 ---\n";
$query = $db->query("SELECT * FROM employers WHERE user_id = 44");
print_r($query->getRowArray() ?: "Not found");
echo "\n";

// 4. Foreign Keys
echo "--- Foreign Keys for conversations table ---\n";
$fks = $db->getForeignKeys('conversations');
if ($fks) {
    foreach ($fks as $fk) {
        echo "{$fk->constraint_name} : {$fk->column_name[0]} -> {$fk->foreign_table_name}.{$fk->foreign_column_name[0]}\n";
    }
} else {
    echo "No foreign keys found on conversations table.\n";
}

echo "</pre>";
