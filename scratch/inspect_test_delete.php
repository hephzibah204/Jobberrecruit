<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$methods = ['inviteToAptitudeTest', 'deleteApplication'];
foreach ($methods as $m) {
    $pos = strpos($content, "function $m");
    if ($pos !== false) {
        echo "=== $m ===\n";
        echo substr($content, $pos, 1500) . "\n\n";
    } else {
        echo "$m NOT FOUND\n";
    }
}
