<?php
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
$app = \CodeIgniter\Boot::bootByCommandLine($paths);
$db = \Config\Database::connect();
print_r($db->getFieldNames('course_enrollments'));
