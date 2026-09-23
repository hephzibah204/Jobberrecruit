<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
try {
    $pdo = new PDO('mysql:host=127.0.0.1;port=3306;dbname=jobberrecruit', 'root', '', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $rows = $pdo->query("SELECT u.id, u.username, u.user_type, ai.secret AS email FROM users u LEFT JOIN auth_identities ai ON u.id = ai.user_id WHERE ai.type = 'email_password'")->fetchAll(PDO::FETCH_ASSOC);
    foreach ($rows as $r) {
        echo $r['id'] . ' | ' . $r['username'] . ' | ' . $r['user_type'] . ' | ' . $r['email'] . "\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage() . "\n";
}
