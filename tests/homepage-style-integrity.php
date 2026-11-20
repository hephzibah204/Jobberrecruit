<?php
$root = dirname(__DIR__);
$view = file_get_contents($root . '/app/Views/home/index.php');
$css = file_get_contents($root . '/css/homepage.css');
if (!preg_match('/<style id="homepage-design">(.*?)<\/style>/s', $view, $match)) {
    throw new RuntimeException('Homepage must include its styling without a separate HTTP request.');
}
if (trim(str_replace("\r\n", "\n", $match[1])) !== trim(str_replace("\r\n", "\n", $css))) {
    throw new RuntimeException('Embedded homepage design has drifted from its CSS source.');
}
if (str_contains($view, "base_url('css/homepage.css')")) {
    throw new RuntimeException('Homepage must not depend on the missing CSS URL.');
}
if (!str_contains($match[1], '.d-none { display: none !important; }')) {
    throw new RuntimeException('Standalone styles must hide Bootstrap utility elements.');
}
echo "PASS: self-contained homepage design matches CSS source and includes required visibility utilities.\n";
