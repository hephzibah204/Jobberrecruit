<?php
$base = dirname(__DIR__);
$envPath = $base . '/.env';
if (is_file($envPath)) {
    foreach (file($envPath, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
        $line = trim($line);
        if ($line === '' || $line[0] === '#') {
            continue;
        }
        if (strpos($line, '=') === false) {
            continue;
        }
        [$name, $value] = array_map('trim', explode('=', $line, 2));
        if ($name === 'GEMINI_API_KEY') {
            $value = trim($value, "\"'");
            putenv($name . '=' . $value);
            $_ENV[$name] = $value;
            break;
        }
    }
}
require $base . '/app/Services/AiService.php';
$service = new \App\Services\AiService();
echo "GENERATE:\n";
echo $service->generate('Reply with exactly: AI_OK'), "\n\n";
echo "CHAT:\n";
echo $service->getChatResponse('Reply with exactly: CHAT_OK'), "\n";
