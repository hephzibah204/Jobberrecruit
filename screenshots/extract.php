<?php
$content = file_get_contents(__DIR__ . '/../app/Views/candidate/resume/builder.php');
preg_match_all('/<script\b[^>]*>(.*?)<\/script>/is', $content, $matches);
$js = implode("\n", $matches[1]);
$file = __DIR__ . '/extracted.js';
file_put_contents($file, $js);
echo "Extracted " . strlen($js) . " bytes of JS to $file\n";
