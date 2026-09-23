<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$pos = strpos($content, 'function editJob');
echo substr($content, $pos, 800);
