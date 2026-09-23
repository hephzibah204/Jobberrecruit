<?php
$content = file_get_contents('app/Controllers/EmployerController.php');
preg_match_all('/public function\s+([a-zA-Z0-9_]+)/', $content, $matches);
echo "=== EmployerController Methods ===\n";
echo implode(', ', $matches[1]) . "\n\n";

$checkItems = [
    'ai-generate' => strpos($content, 'generateJobDescription') !== false,
    'post_job' => strpos($content, 'function post_job') !== false,
    'repostJob' => strpos($content, 'function repostJob') !== false,
    'closeJob' => strpos($content, 'function closeJob') !== false,
    'toggleJobStatus' => strpos($content, 'function toggleJobStatus') !== false,
    'editJob' => strpos($content, 'function editJob') !== false,
    'inviteToAptitudeTest' => strpos($content, 'function inviteToAptitudeTest') !== false,
    'deleteApplication' => strpos($content, 'function deleteApplication') !== false,
    'candidates' => strpos($content, 'function candidates') !== false,
    'unlockCandidate' => strpos($content, 'function unlockCandidate') !== false,
    'aptitudeTests' => strpos($content, 'function aptitudeTests') !== false,
    'settings' => strpos($content, 'function settings') !== false,
    'deactivateAccount' => strpos($content, 'function deactivateAccount') !== false,
    'deleteAccount' => strpos($content, 'function deleteAccount') !== false,
    'notifications' => strpos($content, 'function notifications') !== false,
];
print_r($checkItems);
