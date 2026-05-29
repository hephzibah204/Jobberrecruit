<?php
define('ENVIRONMENT', 'development');

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR);
chdir(__DIR__);
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
\CodeIgniter\Boot::bootConsole($paths);

try {
    $controller = new \App\Controllers\ElearningController();
    // Test with course IDs 1 to 14
    for ($id = 1; $id <= 14; $id++) {
        echo "Testing Course ID: $id... ";
        try {
            $response = $controller->show($id);
            echo "SUCCESS!\n";
        } catch (CodeIgniter\Exceptions\PageNotFoundException $e) {
            echo "404 NOT FOUND (Expected if course inactive/absent)\n";
        } catch (Throwable $e) {
            echo "ERROR: " . $e->getMessage() . "\n";
            echo "FILE: " . $e->getFile() . " LINE: " . $e->getLine() . "\n";
        }
    }
} catch (Throwable $e) {
    echo "ERROR: " . $e->getMessage() . "\n";
    echo "FILE: " . $e->getFile() . " LINE: " . $e->getLine() . "\n";
    echo $e->getTraceAsString() . "\n";
}
