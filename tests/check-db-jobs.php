<?php
define('HOMEPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('CONFIGPATH', HOMEPATH . 'app/Config/');
define('PUBLICPATH', HOMEPATH . 'public/');
require HOMEPATH . 'vendor/codeigniter4/framework/system/Test/bootstrap.php';

$db = \Config\Database::connect('default');
$total = $db->table('jobs')->where('status', 'open')->where('admin_status', 'approved')->countAllResults();
$featured = $db->table('jobs')->where('status', 'open')->where('admin_status', 'approved')->where('is_featured', 1)->countAllResults();
$featuredValid = $db->table('jobs')->where('status', 'open')->where('admin_status', 'approved')->where('is_featured', 1)->where('featured_until >=', date('Y-m-d H:i:s'))->countAllResults();

echo "Total open approved jobs: " . $total . "\n";
echo "Featured jobs count: " . $featured . "\n";
echo "Featured valid until now: " . $featuredValid . "\n";

$rows = $db->table('jobs')->select('id, title, is_featured, featured_until, status, admin_status')
    ->where('status', 'open')
    ->where('admin_status', 'approved')
    ->orderBy('is_featured', 'DESC')
    ->orderBy('featured_until', 'DESC')
    ->limit(15)
    ->get()
    ->getResultArray();

foreach ($rows as $r) {
    echo "ID: {$r['id']}, is_featured: {$r['is_featured']}, until: {$r['featured_until']}, Title: {$r['title']}\n";
}
