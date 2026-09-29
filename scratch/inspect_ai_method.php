<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$pos = strpos($content, 'function generateJobDescription');
echo substr($content, $pos, 1500);
