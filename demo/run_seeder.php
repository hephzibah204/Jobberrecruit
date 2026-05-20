<?php
require 'app/Config/Services.php';
$seeder = new \App\Database\Seeds\IndustrySeeder();
$seeder->run();
echo 'Industry seeding completed.';
?>