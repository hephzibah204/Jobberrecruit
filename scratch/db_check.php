<?php
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
chdir(FCPATH);
require FCPATH . 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootWorker($paths);

$db = db_connect();
$users = $db->query("SELECT users.id, users.username, users.user_type, auth_identities.secret as email FROM users LEFT JOIN auth_identities ON auth_identities.user_id = users.id WHERE users.user_type = 'job_seeker' LIMIT 5")->getResultArray();
echo "JOB SEEKER USERS FOUND:\n";
print_r($users);
