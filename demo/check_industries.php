<?php
include 'app/Config/Services.php';
$db = \Config\Database::connect();
$result = $db->table('industries')->select('id, name, slug')->get();
echo "Industries found: " . $result->getNumRows() . PHP_EOL;
foreach ($result->getResult() as $row) {
    echo 'ID: ' . $row->id . ', Name: ' . $row->name . ', Slug: ' . $row->slug . PHP_EOL;
}