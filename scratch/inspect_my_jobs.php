<?php
$content = file_get_contents('app/Views/employers/my-jobs.php');
echo "Has preview icon check: \n";
preg_match_all('/<a[^>]*preview[^>]*>.*<\/a>/isU', $content, $m);
print_r($m[0]);

echo "\nHas Pause or Close:\n";
preg_match_all('/(pause|close)/i', $content, $m2);
print_r(array_unique($m2[0]));

echo "\nHas Repost:\n";
preg_match_all('/repost/i', $content, $m3);
print_r(array_unique($m3[0]));

echo "\nHas Edit:\n";
preg_match_all('/edit/i', $content, $m4);
print_r(array_unique($m4[0]));
