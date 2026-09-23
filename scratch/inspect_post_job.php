<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$lines = explode("\n", $content);
echo "=== post_job method in EmployerController.php ===\n";
for ($i = 703; $i < 850; $i++) {
    if (isset($lines[$i])) {
        echo ($i + 1) . ": " . $lines[$i] . "\n";
    }
}
