<?php

$filesToProcess = [
    [
        'source' => 'C:/Users/hephz/Downloads/Other pages and Certificates/career-advice.html',
        'target' => 'C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/app/Views/career_advice.php'
    ],
    [
        'source' => 'C:/Users/hephz/Downloads/Other pages and Certificates/cv-review.html',
        'target' => 'C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/app/Views/cv_review.php'
    ],
    [
        'source' => 'C:/Users/hephz/Downloads/Other pages and Certificates/webinars-public.html',
        'target' => 'C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/app/Views/webinars_public.php'
    ],
    [
        'source' => 'C:/Users/hephz/Downloads/Other pages and Certificates/webinar-registered.html',
        'target' => 'C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/app/Views/webinar_registered.php'
    ],
    [
        'source' => 'C:/Users/hephz/Downloads/Other pages and Certificates/verify-certificate.html',
        'target' => 'C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/app/Views/certificates/verify.php'
    ]
];

foreach ($filesToProcess as $fileMap) {
    if (!file_exists($fileMap['source'])) {
        echo "Source not found: {$fileMap['source']}\n";
        continue;
    }

    $html = file_get_contents($fileMap['source']);

    // Extract styles
    preg_match('/<style>(.*?)<\/style>/s', $html, $styleMatch);
    $styles = isset($styleMatch[1]) ? $styleMatch[1] : '';

    // Strip out generic reset and brand tokens because templates/base already has them
    // Or actually we can keep them in scoped section to be safe, but let's just keep them.
    // However, if we keep them it might clash. Let's just keep the unique classes.
    // The previous dev probably just dumped the whole <style> block into the view. Let's do that for fidelity.

    // Extract schema
    preg_match_all('/<script type="application\/ld\+json">(.*?)<\/script>/s', $html, $schemaMatches);
    $schema = '';
    if (!empty($schemaMatches[0])) {
        $schema = implode("\n", $schemaMatches[0]);
    }

    // Extract main content
    preg_match('/<main[^>]*>(.*?)<\/main>/s', $html, $mainMatch);
    $main = isset($mainMatch[0]) ? $mainMatch[0] : '';
    
    // Extract scripts at the bottom
    preg_match('/<\/main>\s*(<script.*?)<\/body>/s', $html, $scriptMatch);
    $scripts = '';
    if (isset($scriptMatch[1])) {
        // remove navbar/footer scripts if they exist, but let's just grab specific custom scripts
        preg_match_all('/<script>(.*?)<\/script>/s', $scriptMatch[1], $inlineScripts);
        if (!empty($inlineScripts[0])) {
            $scripts = implode("\n", $inlineScripts[0]);
        }
    }

    $viewContent = "<?= \$this->extend('templates/base') ?>\n\n";

    if ($schema) {
        $viewContent .= "<?= \$this->section('schema') ?>\n";
        $viewContent .= trim($schema) . "\n";
        $viewContent .= "<?= \$this->endSection() ?>\n\n";
    }

    if ($styles) {
        $viewContent .= "<?= \$this->section('styles') ?>\n";
        $viewContent .= "<style>\n" . trim($styles) . "\n</style>\n";
        $viewContent .= "<?= \$this->endSection() ?>\n\n";
    }

    $viewContent .= "<?= \$this->section('content') ?>\n";
    // Clean up absolute URLs to use base_url()
    $main = preg_replace('/href="\/([^"]+)"/', 'href="<?= base_url(\'$1\') ?>"/', $main);
    $main = preg_replace('/src="\/([^"]+)"/', 'src="<?= base_url(\'$1\') ?>"/', $main);
    
    $viewContent .= trim($main) . "\n";
    $viewContent .= "<?= \$this->endSection() ?>\n\n";

    if ($scripts) {
        // Avoid generic navbar scripts
        if (strpos($scripts, 'nav-dropdown') === false) {
            $viewContent .= "<?= \$this->section('scripts') ?>\n";
            $viewContent .= trim($scripts) . "\n";
            $viewContent .= "<?= \$this->endSection() ?>\n";
        }
    }

    file_put_contents($fileMap['target'], $viewContent);
    echo "Generated: {$fileMap['target']}\n";
}
