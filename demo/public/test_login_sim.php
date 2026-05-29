<?php
// Simulate login POST request
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/login';
$_SERVER['HTTP_HOST'] = 'localhost:8081';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

// Bootstrap CodeIgniter
require __DIR__ . '/../vendor/codeigniter4/framework/system/Boot.php';

// Set test POST data
$_POST = [
    'email' => 'hephzibah204@gmail.com',
    'password' => 'wrong',  // wrong password to test error response
];

echo "SIMULATING LOGIN POST\n\n";

// Check the recaptcha bypass:
// In dev mode, maybe we should skip recaptcha?
echo "ENVIRONMENT: " . ENVIRONMENT . "\n";
echo "recaptcha_site_key: " . (env('recaptcha_site_key') ?: 'NOT SET') . "\n";
echo "recaptcha_secret_key: " . (env('recaptcha_secret_key') ?: 'NOT SET') . "\n";

// Check session config
$sessConfig = new Config\Session();
echo "\nSession driver: " . $sessConfig->driver . "\n";
echo "Session savePath: " . ($sessConfig->savePath ?: '(default)') . "\n";

echo "\nDONE\n";
