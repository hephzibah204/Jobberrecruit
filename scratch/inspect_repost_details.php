<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
$pos1 = strpos($content, 'function repostJob');
echo "=== REPOST JOB ===\n" . substr($content, $pos1, 1200) . "\n\n";

$pos2 = strpos($content, 'function editJob');
echo "=== EDIT JOB ===\n" . substr($content, $pos2, 1000) . "\n\n";

$pos3 = strpos($content, 'function updateJob');
echo "=== UPDATE JOB ===\n" . substr($content, $pos3, 1000) . "\n\n";
