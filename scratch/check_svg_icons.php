<?php
$content = file_get_contents('app/Views/employers/post-job.php');
echo "Has #i-zap definition? " . (strpos($content, 'id="i-zap"') !== false ? "YES" : "NO") . "\n";
// find all svg symbols in post-job.php
preg_match_all('/id="(i-[a-z0-9-]+)"/', $content, $matches);
print_r($matches[1]);
