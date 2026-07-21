<?php
$source = 'C:/Users/hephz/Downloads/Other pages and Certificates/login.html';
$target = 'C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/app/Views/auth/base.php';

$html = file_get_contents($source);

// Extract the <head> block up to </head>
preg_match('/<!DOCTYPE html>.*?<head>(.*?)<\/head>/s', $html, $headMatch);
$head = isset($headMatch[1]) ? trim($headMatch[1]) : '';

// The generic auth layout shouldn't have specific title/description, use variables
$head = preg_replace('/<title>.*?<\/title>/', '<title><?= $title ?? \'JobberRecruit\' ?></title>', $head);
$head = preg_replace('/<meta name="description" content=".*?">/', '', $head);
$head = preg_replace('/<meta property="og:.*?">/', '', $head);
$head = preg_replace('/<link rel="canonical" href=".*?">/', '', $head);

// Fix assets
$head = preg_replace('/href="\/([^"]+)"/', 'href="<?= base_url(\'$1\') ?>"/', $head);

// Extract the header (minimal auth head)
preg_match('/<header class="auth-head">(.*?)<\/header>/s', $html, $headerMatch);
$header = isset($headerMatch[0]) ? $headerMatch[0] : '';
$header = preg_replace('/href="\/([^"]*)"/', 'href="<?= base_url(\'$1\') ?>"/', $header);
$header = preg_replace('/src="\/([^"]+)"/', 'src="<?= base_url(\'$1\') ?>"/', $header);

// Extract the scripts at the bottom
preg_match('/<\/main>\s*(<script.*?)<\/body>/s', $html, $scriptMatch);
$scripts = isset($scriptMatch[1]) ? trim($scriptMatch[1]) : '';

$baseContent = <<<EOT
<!DOCTYPE html>
<html lang="en-NG">
<head>
{$head}
<?= \$this->renderSection('styles') ?>
</head>
<body>

{$header}

<main>
    <?= \$this->renderSection('content') ?>
</main>

{$scripts}
<?= \$this->renderSection('scripts') ?>

</body>
</html>
EOT;

file_put_contents($target, $baseContent);
echo "Auth Base Generated!\n";
