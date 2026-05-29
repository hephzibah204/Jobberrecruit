<?php
error_reporting(E_ALL);
echo "=== reCAPTCHA Keys ===\n";
echo "Site key: " . (env('recaptcha_site_key') ?: 'NOT SET') . "\n";
echo "Secret key: " . (env('recaptcha_secret_key') ?: 'NOT SET') . "\n";

echo "\n=== Database ===\n";
$dbPath = __DIR__ . '/../writable/database.sqlite';
if (!file_exists($dbPath)) {
    $dbPath = __DIR__ . '/../../writable/database.sqlite';
}
echo "DB path: $dbPath\n";
echo "Exists: " . (file_exists($dbPath) ? 'YES' : 'NO') . "\n";

if (file_exists($dbPath)) {
    try {
        $db = new PDO('sqlite:' . $dbPath);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $identity = $db->query("SELECT * FROM auth_identities WHERE type='email_password'")->fetch(PDO::FETCH_ASSOC);
        if ($identity) {
            echo "Identity: user_id={$identity['user_id']} secret={$identity['secret']}\n";
            echo "Hash exists: " . (!empty($identity['secret2']) ? 'YES (len=' . strlen($identity['secret2']) . ')' : 'NO') . "\n";
        } else {
            echo "No email_password identities found\n";
        }
    } catch (Exception $e) {
        echo "Error: " . $e->getMessage() . "\n";
    }
}

echo "\n=== CSRF ===\n";
$sec = new Config\Security();
echo "Protection: " . $sec->csrfProtection . "\n";
echo "Header: " . $sec->headerName . "\n";

echo "\n=== Session ===\n";
require __DIR__ . '/../Config/Session.php';
$s = new Config\Session();
echo "Driver: " . $s->driver . "\n";

echo "\nDONE\n";
