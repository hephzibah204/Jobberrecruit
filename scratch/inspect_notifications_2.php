<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$pos = strpos($content, 'function notifications(');
echo substr($content, $pos + 1200, 1200);
