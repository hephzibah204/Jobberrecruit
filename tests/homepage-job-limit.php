<?php
// Supply more than eight jobs to ensure the view remains bounded independently of SQL.
$GLOBALS['homepageTestJobs'] = array_map(static fn ($id) => (object) [
    'id' => $id, 'title' => 'Position ' . $id, 'employer_name' => 'Employer ' . $id,
    'created_at' => '2026-10-17', 'location' => 'Lagos', 'is_featured' => true,
], range(1, 18));
ob_start();
require __DIR__ . '/homepage-render.php';
$html = ob_get_clean();
preg_match_all('/<article class="job-card\b/', $html, $cards);
if (count($cards[0]) !== 8) throw new RuntimeException('Homepage must render exactly eight cards when enough jobs exist.');
if (!str_contains($html, 'http://homepage.test/jobs/featured')) throw new RuntimeException('Featured listing link missing.');
if (!str_contains($html, 'See more featured jobs')) throw new RuntimeException('See more button missing.');
$controller = file_get_contents(dirname(__DIR__) . '/app/Controllers/Home.php');
$start = strpos($controller, "\$jobsCacheKey = 'homepage_eight_jobs_with_employer_identity_v5'");
if ($start === false) {
    $start = strpos($controller, "\$jobsCacheKey = 'homepage_eight_jobs_with_employer_identity_v4'");
}
$query = substr($controller, $start, strpos($controller, '$employerUserIds = []', $start) - $start);
if (!str_contains($query, '->findAll(8)')) throw new RuntimeException('Homepage SQL must also limit jobs to eight.');
echo "PASS: eight homepage cards from 18 supplied jobs; database limit and featured-listing buttons verified.\n";
