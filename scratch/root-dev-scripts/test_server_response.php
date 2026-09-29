<?php
$url = 'http://localhost:8081/';
echo "Fetching $url ...\n\n";

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HEADER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 10);

$response = curl_exec($ch);
if (curl_errno($ch)) {
    echo 'Curl error: ' . curl_error($ch) . "\n";
} else {
    $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
    $header = substr($response, 0, $header_size);
    $body = substr($response, $header_size);
    
    echo "=== HEADERS ===\n";
    echo $header;
    echo "\n=== BODY (FIRST 1500 CHARACTERS) ===\n";
    echo substr($body, 0, 1500);
    echo "\n...\n";
    
    echo "\n=== CONTAINS OFFLINE OR DB ERROR? ===\n";
    echo "Contains 'DatabaseException': " . (str_contains($body, 'DatabaseException') ? 'YES' : 'NO') . "\n";
    echo "Contains 'unable to open database': " . (str_contains($body, 'unable to open database') ? 'YES' : 'NO') . "\n";
    echo "Contains 'no such column': " . (str_contains($body, 'no such column') ? 'YES' : 'NO') . "\n";
    echo "Contains 'offline': " . (str_contains($body, 'offline') ? 'YES' : 'NO') . "\n";
    echo "Contains 'Trying to reconnect': " . (str_contains($body, 'Trying to reconnect') ? 'YES' : 'NO') . "\n";
    echo "Contains 'Top Hiring Companies': " . (str_contains($body, 'Top Hiring Companies') ? 'YES' : 'NO') . "\n";
}
curl_close($ch);
