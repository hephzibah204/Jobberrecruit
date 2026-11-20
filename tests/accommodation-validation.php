<?php
// Regression test: ensure accommodation options are accepted and not constrained to available/not_available

$files = [
    'app/Controllers/EmployerController.php',
    'auth/app/Controllers/EmployerController.php',
    'ftp_upload/app/Controllers/EmployerController.php',
];

foreach ($files as $file) {
    if (!file_exists($file)) continue;
    $content = file_get_contents($file);
    if (strpos($content, "in_list[available,not_available]") !== false) {
        throw new RuntimeException("Found obsolete in_list[available,not_available] validation rule in {$file}!");
    }
}

// Verify post-job views contain the expected accommodation options
$viewFiles = [
    'app/Views/employers/post-job.php',
    'auth/app/Views/employers/post-job.php',
    'app/Views/employers/edit-job.php',
    'auth/app/Views/employers/edit-job.php',
];

$expectedOptions = [
    'Provided (fully covered by employer)',
    'Provided (cost shared with employee)',
    'Housing allowance provided instead',
    'Not provided',
];

foreach ($viewFiles as $viewFile) {
    if (!file_exists($viewFile)) continue;
    $viewContent = file_get_contents($viewFile);
    foreach ($expectedOptions as $opt) {
        if (strpos($viewContent, $opt) === false) {
            throw new RuntimeException("Missing accommodation option '{$opt}' in {$viewFile}!");
        }
    }
}

echo "PASS: All accommodation validation rules and dropdown options verified successfully.\n";
