<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$lines = explode("\n", $content);

$targets = [539, 704, 1083, 1303, 3011, 3126, 3211, 3373, 3670, 3743, 4145, 4288];
foreach ($targets as $lineNum) {
    echo "=== LINE $lineNum ===\n";
    for ($i = max(0, $lineNum - 5); $i < min(count($lines), $lineNum + 20); $i++) {
        echo ($i + 1) . ": " . $lines[$i] . "\n";
    }
    echo "\n";
}
