<?php
$content = file_get_contents('app/Views/layouts/employer.php');
$pos = strpos($content, 'id="i-external-link"');
if ($pos !== false) {
    echo substr($content, $pos - 10, 300);
} else {
    echo "NOT FOUND";
}
