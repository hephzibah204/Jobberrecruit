<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$pos = strpos($content, 'function inviteToAptitudeTest');
echo substr($content, $pos + 800, 1500);
