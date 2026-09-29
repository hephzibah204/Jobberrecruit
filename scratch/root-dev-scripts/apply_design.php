<?php
$sourcePath = 'C:/Users/hephz/Downloads/Other pages and Certificates/certificate (3).html';
$content = file_get_contents($sourcePath);

// Remove the toolbar
$content = preg_replace('/<!-- Screen-only toolbar -->\s*<div class="cert-toolbar">.*?<\/div>\s*/s', '', $content);

// Replace name
$content = str_replace('<div class="cert-name">Adebayo Martins</div>', '<div class="cert-name"><?= esc($user->full_name ?? $user->username ?? \'Participant\') ?></div>', $content);

// Replace course
$content = str_replace('<div class="cert-course">Mastering the ATS: Build a CV That Gets Interviews</div>', '<div class="cert-course"><?= esc($course->title) ?></div>', $content);

// Replace date
$content = str_replace('<div class="val">15 June 2026</div>', '<div class="val"><?= date(\'F j, Y\', strtotime($certificate[\'issued_at\'])) ?></div>', $content);

// Replace duration
$content = preg_replace(
    '/(<!-- DYNAMIC: backend sets Duration by type .*? -->\s*<div class="cert-meta-item"><div class="lbl">Duration<\/div><div class="val">).*?(<\/div><\/div>)/s',
    '<?php if (!empty($course->duration)): ?>'."\n      ".'$1<?= esc($course->duration) ?>$3'."\n      ".'<?php endif; ?>',
    $content
);

// Replace Certificate ID text
$content = str_replace('<div class="vt-id">JR-CV-2026-8F3A21</div>', '<div class="vt-id"><?= esc($certificate[\'certificate_code\']) ?></div>', $content);
$content = str_replace('<div class="cert-id">JR-CV-2026-8F3A21</div>', '<div class="cert-id"><?= esc($certificate[\'certificate_code\']) ?></div>', $content);

// Replace Verify URLs
$content = str_replace('href="/verify/JR-CV-2026-8F3A21"', 'href="<?= base_url(\'verify/\' . esc($certificate[\'certificate_code\'])) ?>"', $content);
// Also fix the text "Verify at jobberrecruit.com/verify"
$content = str_replace('<div class="vt-url">Verify at jobberrecruit.com/verify</div>', '<div class="vt-url">Verify at <?= base_url(\'verify/\' . esc($certificate[\'certificate_code\'])) ?></div>', $content);

file_put_contents('C:/Users/hephz/Documents/CODEBASE/Jobberrecruit/app/Views/certificates/course_certificate.php', $content);
echo "Successfully updated course_certificate.php\n";
