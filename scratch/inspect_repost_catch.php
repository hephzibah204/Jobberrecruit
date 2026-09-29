<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$pos = strpos($content, 'function repostJob');
echo substr($content, $pos + 1800, 1000);
