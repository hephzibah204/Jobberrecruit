<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "jobberrecruit");

$pass = "Password123!";
$hash = password_hash($pass, PASSWORD_DEFAULT);

$emails = ['admin@test.com', 'employer@test.com', 'candidate@test.com', 'demo.employer@example.com', 'demo.candidate@example.com'];

foreach ($emails as $email) {
    $stmt = $mysqli->prepare("UPDATE auth_identities SET secret2 = ? WHERE secret = ? AND type = 'email_password'");
    $stmt->bind_param("ss", $hash, $email);
    $stmt->execute();
    echo "Reset password for {$email} to Password123! (rows affected: " . $stmt->affected_rows . ")\n";
}
