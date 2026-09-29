<?php
$urls = [
    'http://localhost:8085/career-advice',
    'http://localhost:8085/cv-review',
    'http://localhost:8085/webinars',
    'http://localhost:8085/training/webinars/registered',
    'http://localhost:8085/certificates/verify'
];

foreach ($urls as $url) {
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, true);
    // Remove CURLOPT_NOBODY to do a full GET request
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    echo "$url => HTTP $httpCode\n";
}
