<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$pos = strpos($content, 'function notifications(');
if ($pos !== false) {
    echo substr($content, $pos, 1500);
} else {
    echo "NOT FOUND";
}
