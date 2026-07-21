<?php
// Temporary diagnostic file - DELETE after debugging
// Upload to public_html/debug_route.php and visit https://jobberrecruit.com/debug_route.php

header('Content-Type: text/plain');

echo "=== SERVER DIAGNOSTICS ===\n\n";

// 1. Check mod_rewrite
echo "mod_rewrite loaded: " . (function_exists('apache_get_modules') ? (in_array('mod_rewrite', apache_get_modules()) ? 'YES' : 'NO') : 'Cannot detect (not Apache or no permission)') . "\n";

// 2. Check if physical dirs exist that would block rewrites
$dirs = ['training', 'cv-review', 'cv_review'];
foreach ($dirs as $dir) {
    $path = __DIR__ . '/' . $dir;
    echo "Directory '$dir' exists on disk: " . (is_dir($path) ? 'YES ← THIS BLOCKS ROUTING!' : 'No') . "\n";
}

// 3. Check REQUEST_URI
echo "\nREQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'not set') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'not set') . "\n";
echo "DOCUMENT_ROOT: " . ($_SERVER['DOCUMENT_ROOT'] ?? 'not set') . "\n";

// 4. Check if index.php is reachable
echo "\nindex.php exists: " . (file_exists(__DIR__ . '/index.php') ? 'YES' : 'NO') . "\n";
echo "app/Config/Routes.php exists: " . (file_exists(__DIR__ . '/app/Config/Routes.php') ? 'YES' : 'NO') . "\n";
echo "vendor/ exists: " . (is_dir(__DIR__ . '/vendor') ? 'YES' : 'NO') . "\n";

// 5. PHP version
echo "\nPHP Version: " . PHP_VERSION . "\n";
echo "FCPATH would be: " . __DIR__ . "\n";

// 6. Check .htaccess
echo "\n.htaccess exists: " . (file_exists(__DIR__ . '/.htaccess') ? 'YES' : 'NO') . "\n";

echo "\n=== END ===\n";
echo "\nDELETE this file after debugging!";
