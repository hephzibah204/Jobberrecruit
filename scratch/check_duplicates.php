<?php
require 'vendor/autoload.php';
// Check if EmployerController has duplicate methods and line numbers
$content = file_get_contents('app/Controllers/EmployerController.php');
$lines = explode("\n", $content);
$methods = [];
foreach ($lines as $i => $line) {
    if (preg_match('/public function\s+([a-zA-Z0-9_]+)/', $line, $m)) {
        $methods[$m[1]][] = $i + 1;
    }
}
foreach ($methods as $name => $lns) {
    if (count($lns) > 1) {
        echo "Duplicate method $name at lines: " . implode(', ', $lns) . "\n";
    }
}
