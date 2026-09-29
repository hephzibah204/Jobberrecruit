<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$pos1 = strpos($content, 'function deactivateAccount');
echo "=== deactivateAccount ===\n" . substr($content, $pos1, 800) . "\n\n";

$pos2 = strpos($content, 'function deleteAccount');
echo "=== deleteAccount ===\n" . substr($content, $pos2, 1000) . "\n\n";
