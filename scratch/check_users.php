<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "jobberrecruit");

echo "=== USERS & IDENTITIES ===\n";
$res = $mysqli->query("SELECT u.id, u.username, u.user_type, u.active, i.secret AS email, i.secret2 AS password_hash FROM users u LEFT JOIN auth_identities i ON u.id = i.user_id WHERE i.type = 'email_password'");
while ($row = $res->fetch_assoc()) {
    echo "ID: {$row['id']} | Type: {$row['user_type']} | Email: {$row['email']} | User: {$row['username']}\n";
}

$mysqli->query("UPDATE users SET email_verified_at = NOW() WHERE id IN (2, 34)");
echo "Updated email_verified_at for employer accounts.\n";
