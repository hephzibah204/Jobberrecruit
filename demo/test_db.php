<?php
include 'app/Config/Services.php';
$db = \Config\Database::connect();
$count = $db->table('industries')->countAllResults();
echo "Total industries: " . $count . PHP_EOL;

if ($count > 0) {
    $result = $db->table('industries')->select('id, name, slug')->limit(5)->get();
    foreach ($result->getResult() as $row) {
        echo 'ID: ' . $row->id . ', Name: ' . $row->name . ', Slug: ' . $row->slug . PHP_EOL;
    }
} else {
    echo "No industries found in database." . PHP_EOL;
}
?>