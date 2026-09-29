<?php
$content = file_get_contents('app/Views/employers/my-jobs.php');
$lines = explode("\n", $content);
foreach ($lines as $i => $l) {
    if (stripos($l, 'actions') !== false || stripos($l, 'preview') !== false || stripos($l, 'view_job') !== false || stripos($l, 'job/') !== false || stripos($l, 'pause') !== false || stripos($l, 'close') !== false) {
        echo ($i + 1) . ": " . trim($l) . "\n";
    }
}
