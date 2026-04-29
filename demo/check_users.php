<?php
$c = new mysqli('localhost', 'root', '', 'jobber_db');
$res = $c->query('DESCRIBE users');
while($row = $res->fetch_assoc()) {
    echo "Field: " . $row['Field'] . " | Type: " . $row['Type'] . "\n";
}
$c->close();
