<?php
// Simple DB check script
$conn = new mysqli("127.0.0.1", "root", "", "jobberrecruit", 3306);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT u.id, u.username, u.active, ui.secret AS email, ui.secret2 AS password_hash 
                        FROM users u 
                        LEFT JOIN auth_identities ui ON u.id = ui.user_id 
                        LIMIT 5");

$users = [];
if ($result) {
    while ($row = $result->fetch_assoc()) {
        $users[] = $row;
    }
}

header('Content-Type: application/json');
echo json_encode($users, JSON_PRETTY_PRINT);
$conn->close();
