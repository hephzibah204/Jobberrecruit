<?php
require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../app/Services/AiService.php';
$s = new \App\Services\AiService();
echo $s->generate('hello'), PHP_EOL;
