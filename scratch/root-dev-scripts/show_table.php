<?php
$conn = new mysqli('127.0.0.1', 'root', 'WaitOnGod2026', 'jobberrecruit', 3306);
$result = $conn->query("SHOW CREATE TABLE jobs");
$row = $result->fetch_assoc();
echo $row['Create Table'];
$conn->close();
