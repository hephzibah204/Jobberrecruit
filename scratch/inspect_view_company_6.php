<?php
$content = file_get_contents('app/Controllers/Home.php');
$pos = strpos($content, 'function viewCompany');
echo substr($content, $pos + 3800, 600);
