<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$methods = ['repostJob', 'closeJob', 'editJob', 'updateJob'];
foreach ($methods as $m) {
    $pos = strpos($content, "function $m");
    if ($pos !== false) {
        echo "=== $m ===\n";
        echo substr($content, $pos, 800) . "\n\n";
    } else {
        echo "$m NOT FOUND\n";
    }
}
