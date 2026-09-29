<?php
define('COMPOSER_PATH', __DIR__ . '/../vendor/autoload.php');
define('FCPATH', __DIR__ . '/../public/');
define('APPPATH', __DIR__ . '/../app/');
define('SYSTEMPATH', __DIR__ . '/../vendor/codeigniter4/framework/system/');
define('ROOTPATH', __DIR__ . '/../');
define('WRITEPATH', __DIR__ . '/../writable/');
define('ENVIRONMENT', 'development');

require_once SYSTEMPATH . 'bootstrap.php';

$m = model(\App\Models\JobSeekerModel::class);
$candidates = $m->findAll();
foreach ($candidates as $c) {
    echo "ID: {$c->id}, UserID: {$c->user_id}, Name: {$c->full_name}, Completion: {$c->getProfileCompletion()}%\n";
}
