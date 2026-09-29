<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$lines = explode("\n", $content);
echo "=== post_job GET part in EmployerController.php ===\n";
for ($i = 880; $i < 970; $i++) {
    if (isset($lines[$i])) {
        echo ($i + 1) . ": " . $lines[$i] . "\n";
    }
}
