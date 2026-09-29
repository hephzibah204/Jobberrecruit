<?php
$content = file_get_contents('app/Views/employers/post-job.php');
$lines = explode("\n", $content);
foreach ($lines as $i => $l) {
    if (stripos($l, 'Job description') !== false || stripos($l, 'AI generate') !== false || stripos($l, 'ai-generate') !== false || stripos($l, 'generateJobDescription') !== false) {
        echo ($i + 1) . ": " . trim($l) . "\n";
    }
}
