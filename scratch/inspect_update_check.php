<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$pos = strpos($content, 'function updateJob');
echo substr($content, $pos + 500, 600);
