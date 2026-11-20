<?php
// Verify the query/view field contract and rendered public/anonymous names.
$controller = file_get_contents(dirname(__DIR__) . '/app/Controllers/Home.php');
$home = substr($controller, strpos($controller, 'public function home()'));
$home = substr($home, 0, strpos($home, 'public function location_hub'));
if (!str_contains($home, 'employers.company_name as employer_name')) {
    throw new RuntimeException('Homepage query must fetch the name expected by the cards.');
}
if (!str_contains($home, "\$jobsCacheKey = 'homepage_eight_jobs_with_employer_identity_v5'") && !str_contains($home, "\$jobsCacheKey = 'homepage_eight_jobs_with_employer_identity_v4'")) {
    throw new RuntimeException('Old cached rows without company names must be bypassed.');
}
$GLOBALS['homepageTestJobs'] = [
    (object) ['id' => 1, 'title' => 'Engineer', 'employer_name' => 'Acme & Partners', 'show_trust_badge' => true, 'created_at' => '2026-10-17', 'location' => 'Lagos'],
    (object) ['id' => 2, 'title' => 'Designer', 'employer_name' => 'Private Employer Name', 'show_trust_badge' => true, 'anonymous' => true, 'created_at' => '2026-10-17', 'location' => 'Abuja'],
];
ob_start();
require __DIR__ . '/homepage-render.php';
$html = ob_get_clean();
preg_match_all('/class="job-company-name">([^<]*)</', $html, $cards);
if ($cards[1] !== ['Acme &amp; Partners', 'Confidential Employer']) {
    throw new RuntimeException('Incorrect company names in homepage cards.');
}
if (str_contains($html, 'Private Employer Name')) throw new RuntimeException('Anonymous employer leaked.');
if (substr_count($html, 'class="verified-check"') !== 1) throw new RuntimeException('Badge must display for eligible public employers only.');
if (!preg_match('/class="ticker-co">· Acme &amp; Partners</', $html)) throw new RuntimeException('Ticker company name missing.');
echo "PASS: company query field, cache refresh, card/ticker names, escaping and anonymous employer privacy.\n";
