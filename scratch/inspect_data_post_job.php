<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$lines = explode("\n", $content);
for ($i = 964; $i < 1000; $i++) {
    if (isset($lines[$i])) {
        echo ($i + 1) . ": " . $lines[$i] . "\n";
    }
}
