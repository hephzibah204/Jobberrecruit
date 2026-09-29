<?php
define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);
$start = microtime(true);

require FCPATH . 'app/Config/Paths.php';
echo '1. Paths loaded: ' . round(microtime(true) - $start, 4) . "s\n";

$paths = new Config\Paths();
echo '2. Paths instantiated: ' . round(microtime(true) - $start, 4) . "s\n";

require $paths->systemDirectory . '/Boot.php';
echo '3. Boot loaded: ' . round(microtime(true) - $start, 4) . "s\n";

echo "4. Calling bootWeb...\n";
flush();
$app = \CodeIgniter\Boot::bootWeb($paths);
echo '5. App booted: ' . round(microtime(true) - $start, 4) . "s\n";
