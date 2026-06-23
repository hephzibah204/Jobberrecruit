<?php
$c = file_get_contents('app/Views/admin/newsletters/index.php');
$o = substr_count($c, '<div');
$cc = substr_count($c, '</div');
echo "Opened: $o, Closed: $cc\n";
