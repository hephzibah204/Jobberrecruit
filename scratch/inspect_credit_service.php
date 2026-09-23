<?php
$content = file_get_contents('app/Services/CreditService.php');
$methods = ['getCurrentPlan', 'hasUnlimitedAccess', 'getCurrentSubscription'];
foreach ($methods as $m) {
    $pos = strpos($content, "function $m");
    if ($pos !== false) {
        echo "=== $m ===\n";
        echo substr($content, $pos, 600) . "\n\n";
    }
}
