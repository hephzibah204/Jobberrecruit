<?php
$mysqli = new mysqli("127.0.0.1", "root", "WaitOnGod2026", "jobberrecruit", 3306);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}
$result = $mysqli->query("SELECT id, title, duration FROM courses LIMIT 5");
while ($row = $result->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | Title: " . $row['title'] . " | Duration: " . ($row['duration'] ?: "EMPTY") . "\n";
}
$mysqli->close();
