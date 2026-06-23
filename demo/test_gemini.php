<?php

// Read .env manually
$apiKey = '';
$model = 'gemini-2.5-flash';

if (fs_exists_env()) {
    $envLines = explode("\n", file_get_contents('.env'));
    foreach ($envLines as $line) {
        $line = trim($line);
        if (strpos($line, 'GEMINI_API_KEY') === 0) {
            $parts = explode('=', $line, 2);
            $apiKey = trim($parts[1] ?? '', " \t\n\r\0\x0B\"'");
        }
        if (strpos($line, 'GEMINI_MODEL') === 0) {
            $parts = explode('=', $line, 2);
            $model = trim($parts[1] ?? '', " \t\n\r\0\x0B\"'");
        }
    }
}

function fs_exists_env() {
    return file_exists('.env');
}

if (empty($apiKey)) {
    die("GEMINI_API_KEY not found in .env\n");
}

echo "Using API Key (first 5 and last 5): " . substr($apiKey, 0, 5) . "..." . substr($apiKey, -5) . "\n";
echo "Using Model: " . $model . "\n";

$url = 'https://generativelanguage.googleapis.com/v1beta/models/' . $model . ':generateContent?key=' . $apiKey;

$prompt = "Write a 2-sentence professional resume summary for a PHP Web Developer with 5 years experience who knows React and SQL.";
$payload = [
    'contents' => [
        [
            'parts' => [
                ['text' => $prompt]
            ]
        ]
    ]
];

echo "Sending request to Gemini API...\n";
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

$response = curl_exec($ch);
$err = curl_error($ch);
$errno = curl_errno($ch);
$http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($err) {
    echo "cURL Error: " . $err . "\n";
} else {
    echo "HTTP Status Code: " . $http_status . "\n";
    echo "Response:\n";
    $result = json_decode($response, true);
    if (isset($result['candidates'][0]['content']['parts'][0]['text'])) {
        echo $result['candidates'][0]['content']['parts'][0]['text'] . "\n";
    } else {
        echo "Failed to get text. Full response:\n";
        print_r($result);
    }
}
