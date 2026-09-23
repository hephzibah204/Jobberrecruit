<?php
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootWorker($paths);

$db = db_connect();
$plans = $db->table('plans')->get()->getResultArray();
echo "=== PLANS ===\n";
foreach ($plans as $p) {
    echo "ID: {$p['id']}, Name: {$p['name']}, Slug: {$p['slug']}, Features: {$p['features']}\n";
}

$bundles = $db->table('plan_bundles')->get()->getResultArray();
echo "\n=== BUNDLES ===\n";
foreach ($bundles as $b) {
    echo "ID: {$b['id']}, Name: {$b['name']}, Slug: {$b['slug']}, Credits: {$b['credits']}\n";
}
