<?php
define('FCPATH', __DIR__ . '/../public/');
chdir(__DIR__ . '/../');
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require rtrim($paths->systemDirectory, '\\/ ') . '/bootstrap.php';

$model = new \App\Models\PlanModel();
$plans = $model->where('plan_type', 'candidate')->where('is_active', 1)->findAll();
print_r($plans);
