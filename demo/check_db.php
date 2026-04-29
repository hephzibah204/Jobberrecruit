<?php

namespace App\Controllers;

use Config\Database;

require_once __DIR__ . '/public/index.php';

$db = Database::connect();

echo "--- WEBINARS ---\n";
$webinars = $db->table('webinars')->get()->getResult();
print_r($webinars);

echo "\n--- SUBSCRIPTION PLANS ---\n";
$plans = $db->table('subscription_plans')->get()->getResult();
print_r($plans);

echo "\n--- RECENT JOBS ---\n";
$jobs = $db->table('jobs')->limit(5)->get()->getResult();
print_r($jobs);
