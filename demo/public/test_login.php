<?php
// Test login flow directly via PHP without a web server
$_SERVER['REQUEST_METHOD'] = 'POST';
$_SERVER['REQUEST_URI'] = '/login';
$_SERVER['HTTP_HOST'] = 'localhost:8081';
$_SERVER['REMOTE_ADDR'] = '127.0.0.1';

// Minimal bootstrap to test the login controller
require __DIR__ . '/../vendor/autoload.php';

// Set environment
define('ENVIRONMENT', 'development');

// Load paths
require __DIR__ . '/../app/Config/Paths.php';
$paths = new Config\Paths();

// Setup
$appConfig = require __DIR__ . '/../app/Config/App.php';
$app = new Config\App();

// Check reCAPTCHA keys
echo "=== reCAPTCHA Check ===\n";
echo "Site key: " . (env('recaptcha_site_key') ?: 'NOT SET') . "\n";
echo "Secret key: " . (env('recaptcha_secret_key') ?: 'NOT SET') . "\n";

// Check if file_get_contents can reach Google
echo "\n=== Network Check ===\n";
$ctx = stream_context_create(['http' => ['timeout' => 5]]);
$test = @file_get_contents('https://www.google.com/recaptcha/api/siteverify?secret=test&response=test', false, $ctx);
if ($test === false) {
    echo "Cannot reach Google reCAPTCHA API - likely blocked/no internet\n";
    echo "Error: " . (error_get_last()['message'] ?? 'unknown') . "\n";
} else {
    echo "Google reCAPTCHA API reachable\n";
}

// Check auth identities table
echo "\n=== Database Check ===\n";
try {
    $dbPath = __DIR__ . '/../../writable/database.sqlite';
    if (!file_exists($dbPath)) {
        $dbPath = __DIR__ . '/../writable/database.sqlite';
    }
    echo "DB path: $dbPath\n";
    $db = new PDO('sqlite:' . $dbPath);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $identity = $db->query("SELECT * FROM auth_identities WHERE type='email_password' AND secret='hephzibah204@gmail.com'")->fetch(PDO::FETCH_ASSOC);
    if ($identity) {
        echo "Identity found: user_id={$identity['user_id']}\n";
        echo "Hash: " . substr($identity['secret2'], 0, 30) . "...\n";
        
        // Test password verification
        echo "\n=== Password Verification ===\n";
        $passLib = new CodeIgniter\Shield\Authentication\Passwords();
        $verifyResult = $passLib->verify('wrongpassword', $identity['secret2']);
        echo "Wrong password verify: " . ($verifyResult ? 'PASS' : 'FAIL') . "\n";
        
        // We don't know the real password, let's note that
        echo "NOTE: Can't test correct password without knowing it\n";
    } else {
        echo "No identity found for hephzibah204@gmail.com\n";
        
        // List all identities
        $all = $db->query("SELECT * FROM auth_identities")->fetchAll(PDO::FETCH_ASSOC);
        echo "All identities:\n";
        foreach ($all as $a) {
            echo "  user_id={$a['user_id']} type={$a['type']} secret={$a['secret']}\n";
        }
    }
} catch (Exception $e) {
    echo "DB Error: " . $e->getMessage() . "\n";
}

echo "\n=== CSRF Check ===\n";
$secConfig = require __DIR__ . '/../app/Config/Security.php';
$sec = new Config\Security();
echo "CSRF Protection: " . $sec->csrfProtection . "\n";
echo "Token name: " . $sec->tokenName . "\n";
echo "Header name: " . $sec->headerName . "\n";

echo "\n=== Filters Check ===\n";
// Check if CSRF filter is in globals
$filtersCode = file_get_contents(__DIR__ . '/../app/Config/Filters.php');
if (strpos($filtersCode, "'csrf'") !== false) {
    echo "CSRF filter referenced in Filters.php\n";
    if (preg_match('/globals.*?before.*?csrf/s', $filtersCode)) {
        echo "CSRF filter IS in globals before\n";
    } else {
        echo "CSRF filter NOT in globals before (likely commented out)\n";
    }
} else {
    echo "CSRF filter NOT referenced in Filters.php\n";
}

echo "\n=== Session Check ===\n";
$sessConfig = require __DIR__ . '/../app/Config/Session.php';
$sess = new Config\Session();
echo "Driver: " . $sess->driver . "\n";
echo "Save path: " . ($sess->savePath ?: '(default)') . "\n";
