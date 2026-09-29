<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$pos = strpos($content, 'function candidates(');
echo substr($content, $pos, 1500);
