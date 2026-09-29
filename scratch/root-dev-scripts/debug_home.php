<?php
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/';
$_SERVER['SERVER_NAME'] = 'localhost';
$_SERVER['SERVER_PORT'] = '8081';
$_SERVER['HTTPS'] = 'off';

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';

// Override exit so we can continue after bootWeb
// Actually bootWeb exits, so let's just run the web request and see
echo "Starting bootWeb...\n";
flush();
ob_start();
\CodeIgniter\Boot::bootWeb($paths);
$output = ob_get_clean();
echo "Done. Output length: " . strlen($output) . "\n";
