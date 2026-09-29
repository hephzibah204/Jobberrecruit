<?php
$content = file_get_contents('app/Controllers/Home.php');
$pos = strpos($content, 'function viewCompany');
if ($pos !== false) {
    echo substr($content, $pos, 1000);
} else {
    echo "NOT FOUND";
}
