<?php
$base = getcwd();
$key = null;
foreach (file($base . '/.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
    $line = trim($line);
    if ($line === '' || $line[0] === '#' || strpos($line, '=') === false) {
        continue;
    }
    [$name, $value] = array_map('trim', explode('=', $line, 2));
    if ($name === 'GEMINI_API_KEY') {
        $key = trim($value, "\"'");
        break;
    }
}
if (!$key) {
    fwrite(STDERR, "No key\n");
    exit(1);
}
$url = 'https://generativelanguage.googleapis.com/v1beta/models?key=' . rawurlencode($key);
$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
$err = curl_error($ch);
curl_close($ch);
if ($err) {
    fwrite(STDERR, $err . "\n");
    exit(1);
}
$data = json_decode($response, true);
if (!empty($data['models'])) {
    foreach (array_slice($data['models'], 0, 15) as $model) {
        echo ($model['name'] ?? 'unknown'), ' | ', implode(',', $model['supportedGenerationMethods'] ?? []), PHP_EOL;
    }
} else {
    echo $response, PHP_EOL;
}
