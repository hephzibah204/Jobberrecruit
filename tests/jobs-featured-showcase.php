<?php
define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APPPATH', FCPATH . 'app/');
function base_url($path = '') { return 'http://jobs.test/' . ltrim($path, '/'); }
function current_url() { return base_url('jobs'); }
function esc($value, $context = 'html') { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
function uri_string() { return 'jobs'; }
function csrf_hash() { return 'fixture'; }
function auth() { return new class { function user() { return null; } function loggedIn() { return false; } }; }
function humanize($value) { return ucwords(str_replace(['-', '_'], ' ', $value)); }
function format_job_url($job) { return base_url('job/view/' . $job->id); }
function jobPostingSchema($job, $base) { return ['@type' => 'JobPosting', 'title' => $job->title]; }

class JobsShowcaseTest
{
    public array $sections = [];
    private string $active = '';
    function extend($name) {}
    function section($name) { $this->active = $name; ob_start(); }
    function endSection() { $this->sections[$this->active] = ob_get_clean(); }

    function test()
    {
        $auth = auth(); $keywords = ''; $stateId = ''; $categoryId = ''; $industryId = '';
        $jobType = ''; $jobPosted = ''; $experienceLevel = ''; $salaryMin = ''; $salaryMax = ''; $workArrangement = '';
        $sort_by = 'newest'; $view_mode = 'grid'; $current_page = 1; $per_page = 24; $total_jobs = 12;
        $savedJobIds = []; $job_type_counts = ['full-time' => 12];
        $industry_counts = [(object) ['id' => 1, 'job_count' => 12]];
        $industries = [(object) ['id' => 1, 'name' => 'Technology']];
        $categories = [(object) ['id' => 1, 'name' => 'Engineering']];
        $states = [(object) ['id' => 1, 'name' => 'Lagos']];

        // 8 Featured jobs followed by 4 standard jobs
        $jobs = array_map(static fn($id) => (object) [
            'id' => $id,
            'title' => 'Job Title ' . $id,
            'slug' => 'role-' . $id,
            'employer_name' => 'Company ' . $id,
            'created_at' => '2026-10-19',
            'location' => 'Lagos',
            'is_featured' => ($id <= 8) ? 1 : 0,
            'featured_until' => ($id <= 8) ? '2027-04-19 00:00:00' : null,
            'show_trust_badge' => true,
            'anonymous' => false,
            'is_anonymous' => false,
            'job_type' => 'full-time',
            'salary' => 250000,
            'salary_details' => '₦250,000 / month',
            'application_method' => 'internal',
        ], range(1, 12));

        ob_start();
        require APPPATH . 'Views/home/jobs.php';
        ob_end_clean();

        $content = $this->sections['content'];

        // 1. Verify 12 cards total
        preg_match_all('/<article class="([^"]*job-card[^"]*)"/', $content, $cards);
        if (count($cards[0]) !== 12) {
            throw new RuntimeException("Expected 12 cards, found " . count($cards[0]));
        }

        // 2. Verify top 8 cards are featured
        for ($i = 0; $i < 8; $i++) {
            if (!str_contains($cards[1][$i], 'job-card--featured')) {
                throw new RuntimeException("Card " . ($i + 1) . " should have job-card--featured class!");
            }
        }

        // 3. Verify cards 9-12 are NOT featured
        for ($i = 8; $i < 12; $i++) {
            if (str_contains($cards[1][$i], 'job-card--featured')) {
                throw new RuntimeException("Card " . ($i + 1) . " should NOT have job-card--featured class!");
            }
        }

        // 4. Verify 8 badge-featured in grid view
        $featuredBadgeCount = substr_count($content, 'badge-featured');
        if ($featuredBadgeCount !== 8) {
            throw new RuntimeException("Expected 8 badge-featured, found {$featuredBadgeCount}");
        }

        // 5. Verify 8 badge-promoted in list view
        $promotedBadgeCount = substr_count($content, 'badge-promoted');
        if ($promotedBadgeCount !== 8) {
            throw new RuntimeException("Expected 8 badge-promoted, found {$promotedBadgeCount}");
        }

        // 6. Verify in-feed ad is present
        if (!str_contains($content, 'class="results-ad"')) {
            throw new RuntimeException("Results ad missing!");
        }

        // 7. Verify ad position: between card 8 and card 9
        $adPos = strpos($content, 'class="results-ad"');
        $card8Pos = strpos($content, 'aria-label="Job Title 8');
        $card9Pos = strpos($content, 'aria-label="Job Title 9');
        if (!($card8Pos < $adPos && $adPos < $card9Pos)) {
            throw new RuntimeException("In-feed ad must be positioned strictly after Card 8 and before Card 9!");
        }

        echo "PASS: All 8 featured jobs verified with .job-card--featured, .badge-featured, .badge-promoted, and in-feed ad correctly positioned after Card 8.\n";
    }
}

(new JobsShowcaseTest())->test();
