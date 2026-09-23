<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$methods = ['shouldFeatureJob', 'hasUnlimitedAccess', 'checkJobPostingAccess', 'canUseAnonymousPosting'];
foreach ($methods as $m) {
    $pos = strpos($content, "function $m");
    if ($pos !== false) {
        echo "=== $m ===\n";
        echo substr($content, $pos, 600) . "\n\n";
    } else {
        echo "$m NOT FOUND\n";
    }
}
