<?php
$pdo = new PDO('sqlite:../writable/database.sqlite');
echo "Employers:\n";
foreach ($pdo->query("SELECT id, logo FROM employers LIMIT 5") as $row) {
    echo $row['id'] . ": " . $row['logo'] . "\n";
}
echo "\nJob Seekers:\n";
foreach ($pdo->query("SELECT id, profile_picture FROM job_seekers LIMIT 5") as $row) {
    echo $row['id'] . ": " . $row['profile_picture'] . "\n";
}
