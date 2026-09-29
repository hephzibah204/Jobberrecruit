<?php
$mysqli = new mysqli('localhost', 'root', '', 'jobberrecruit');
if ($mysqli->connect_error) {
    die('Connect Error: ' . $mysqli->connect_error);
}

$hash = password_hash('AdminPassword123', PASSWORD_BCRYPT);

// Update user 21 admin@test.com
$stmt = $mysqli->prepare("UPDATE auth_identities SET secret2 = ? WHERE user_id = 21");
$stmt->bind_param('s', $hash);
$stmt->execute();

// Check if admin@jobberrecruit.com identity exists
$res = $mysqli->query("SELECT id FROM auth_identities WHERE secret = 'admin@jobberrecruit.com'");
if ($res->num_rows > 0) {
    $stmt = $mysqli->prepare("UPDATE auth_identities SET secret2 = ? WHERE secret = 'admin@jobberrecruit.com'");
    $stmt->bind_param('s', $hash);
    $stmt->execute();
} else {
    $stmt = $mysqli->prepare("INSERT INTO auth_identities (user_id, type, secret, secret2, created_at, updated_at) VALUES (21, 'email_password', 'admin@jobberrecruit.com', ?, NOW(), NOW())");
    $stmt->bind_param('s', $hash);
    $stmt->execute();
}

echo "Admin credentials updated successfully.\n";
