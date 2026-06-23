<?php
define('FCPATH', __DIR__ . '/public/');
require 'vendor/autoload.php';
require 'system/bootstrap.php';
$db = \Config\Database::connect();
$employers = $db->query("SELECT id, logo FROM employers WHERE logo IS NOT NULL LIMIT 5")->getResultArray();
$candidates = $db->query("SELECT id, profile_picture FROM job_seekers WHERE profile_picture IS NOT NULL LIMIT 5")->getResultArray();
echo "Employers:\n";
print_r($employers);
echo "Candidates:\n";
print_r($candidates);
