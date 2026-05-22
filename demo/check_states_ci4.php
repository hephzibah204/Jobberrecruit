<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
require_once __DIR__ . '/vendor/codeigniter4/framework/system/bootstrap.php';

use App\Models\StateModel;
use Config\Database;

$config = new Database();
echo "Active DB group: " . $config->defaultGroup . "\n";
$db = Database::connect();
echo "DB Driver: " . $db->getPlatform() . "\n";
if ($db->getPlatform() === 'SQLite3') {
    echo "SQLite File/DB Config: " . $db->database . "\n";
}

$stateModel = model(StateModel::class);
$states = $stateModel->limit(5)->findAll();
echo "States count: " . count($states) . "\n";
foreach ($states as $state) {
    echo "State ID: {$state->id}, Name: {$state->name}, Slug: {$state->slug}\n";
}
