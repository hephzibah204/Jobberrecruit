<?php
$html = file_get_contents('http://localhost:8081/');

echo "=== EXTRACTED LOCATION LINKS ===\n";
preg_match_all('/href="([^"]*jobs-in-[^"]*)"/', $html, $matches);
foreach (array_unique($matches[1]) as $link) {
    echo $link . "\n";
}

echo "\n=== EXTRACTED CATEGORY LINKS ===\n";
preg_match_all('/href="([^"]*jobs[^"]*)"/', $html, $matches);
foreach (array_unique($matches[1]) as $link) {
    if (!str_contains($link, 'jobs-in-')) {
        echo $link . "\n";
    }
}
