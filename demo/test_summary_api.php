<?php

// Bootstrap CI4
define('FCPATH', __DIR__ . '/public/');
require __DIR__ . '/app/Config/Paths.php';
$paths = new Config\Paths();
require __DIR__ . '/vendor/codeigniter4/framework/system/bootstrap.php';

$aiService = new \App\Services\AiService();
echo "Testing AiService summary generation...\n";

$experiences = [
    ['company' => 'Google', 'position' => 'Senior Engineer', 'description' => 'Developed high-performance web applications using PHP and React.']
];
$skills = 'PHP, React, SQL, Cloud Architecture';
$education = [
    ['school' => 'MIT', 'degree' => 'Bachelor', 'field' => 'Computer Science']
];

try {
    $result = $aiService->generateProfessionalSummary($experiences, $skills, $education);
    echo "\n=== AI Summary Result ===\n";
    var_dump($result);
    echo "=========================\n";
} catch (\Exception $e) {
    echo "Exception: " . $e->getMessage() . "\n";
}
