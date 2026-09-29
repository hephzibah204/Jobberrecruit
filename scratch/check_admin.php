<?php
require 'vendor/autoload.php';
require 'app/Config/Paths.php';
$paths = new Config\Paths();
require $paths->systemDirectory . '/Boot.php';
CodeIgniter\Boot::bootTest($paths);

$db = \Config\Database::connect();
$users = $db->table('users')->where('user_type', 'admin')->get()->getResult();
foreach ($users as $u) {
    echo "Admin: ID={$u->id}, email={$u->email}, username={$u->username}, active={$u->active}\n";
}
