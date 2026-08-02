<?php
require 'vendor/autoload.php';
use Spatie\Browsershot\Browsershot;

$outputFile = 'test_browsershot.pdf';
if (file_exists($outputFile)) @unlink($outputFile);

try {
    Browsershot::html('<h1>Hello Browsershot</h1>')
        ->noSandbox()
        ->save($outputFile);
    echo "Success: " . filesize($outputFile) . " bytes\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
