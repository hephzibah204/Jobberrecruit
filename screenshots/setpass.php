<?php
// Script to set password 'Password123!' for testcandidate (user_id 6)
error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=jobberrecruit', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $hash = password_hash('Password123!', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("UPDATE auth_identities SET secret2 = ? WHERE user_id = 6 AND type = 'email_password'");
    $stmt->execute([$hash]);
    echo "Password for user 6 (candidate@test.com) updated to Password123!\n";
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
