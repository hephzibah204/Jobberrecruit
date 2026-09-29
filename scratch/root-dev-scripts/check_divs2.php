<?php
$c = file_get_contents('app/Views/admin/elearning/index.php');
$o = substr_count($c, '<div');
$cc = substr_count($c, '</div');
echo "elearning/index Opened: $o, Closed: $cc\n";

$c = file_get_contents('app/Views/admin/elearning/certificate_editor.php');
$o = substr_count($c, '<div');
$cc = substr_count($c, '</div');
echo "certificate_editor Opened: $o, Closed: $cc\n";
