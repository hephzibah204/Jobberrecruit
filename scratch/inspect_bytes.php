<?php
$mysqli = new mysqli("127.0.0.1", "root", "", "jobberrecruit");
$res = $mysqli->query("SELECT id, body FROM question_options WHERE body LIKE '%2,500%'");
while ($row = $res->fetch_assoc()) {
    echo "ID: " . $row['id'] . " | HEX: " . bin2hex($row['body']) . " | STRING: " . $row['body'] . "\n";
}

$res2 = $mysqli->query("SELECT id, body FROM questions WHERE body LIKE '%2,500%'");
while ($row = $res2->fetch_assoc()) {
    echo "Q ID: " . $row['id'] . " | HEX: " . bin2hex($row['body']) . " | STRING: " . $row['body'] . "\n";
}
