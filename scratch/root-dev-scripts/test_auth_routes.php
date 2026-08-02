<?php
$urls = [
    'http://localhost:8085/login',
    'http://localhost:8085/register',
    'http://localhost:8085/forgot-password',
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "$url => HTTP $httpCode\n";
}
