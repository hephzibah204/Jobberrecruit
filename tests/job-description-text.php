<?php
require __DIR__ . '/../app/Helpers/job_description_helper.php';
$cases = [
    ['<p>Software Engineer</p><p><br></p><p><br></p><p>Build &amp; maintain.</p>', "Software Engineer\n\nBuild & maintain."],
    ['<h2>Skills</h2><ul><li>PHP</li><li>SQL</li></ul>', "Skills\n\n• PHP\n• SQL"],
    ["Plain text\nSalary < 50000 & experience > 2", "Plain text\nSalary < 50000 & experience > 2"],
    ['<p>Safe</p><script>alert(1)</script>', 'Safe'],
    [null, ''],
];
foreach ($cases as [$input, $expected]) {
    if (job_description_text($input) !== $expected) {
        throw new RuntimeException('Description conversion failed: ' . var_export($input, true));
    }
}
echo "PASS: paragraphs, bullets, entities, plain text, and script removal.\n";
