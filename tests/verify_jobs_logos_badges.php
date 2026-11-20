<?php

define('FCPATH', dirname(__DIR__) . DIRECTORY_SEPARATOR);
define('APPPATH', FCPATH . 'app/');

if (!function_exists('base_url')) {
    function base_url($path = '') { return 'http://jobs.test/' . ltrim($path, '/'); }
}
if (!function_exists('current_url')) {
    function current_url() { return base_url('jobs'); }
}
if (!function_exists('esc')) {
    function esc($value, $context = 'html') { return htmlspecialchars((string) $value, ENT_QUOTES, 'UTF-8'); }
}
if (!function_exists('uri_string')) {
    function uri_string() { return 'jobs'; }
}
if (!function_exists('csrf_hash')) {
    function csrf_hash() { return 'fixture-token'; }
}
if (!function_exists('auth')) {
    function auth() { return new class { function user() { return null; } function loggedIn() { return false; } }; }
}
if (!function_exists('humanize')) {
    function humanize($value) { return ucwords(str_replace(['-', '_'], ' ', $value)); }
}
if (!function_exists('format_job_url')) {
    function format_job_url($job) { return base_url('jobs/' . ($job->slug ?? $job->id)); }
}

class TestJobsView
{
    public array $sections = [];
    private string $active = '';

    function extend($name) {}
    function section($name) { $this->active = $name; ob_start(); }
    function endSection() { $this->sections[$this->active] = ob_get_clean(); }

    function renderAndVerify(): bool
    {
        $auth = auth();
        $keywords = ''; $stateId = ''; $categoryId = ''; $industryId = '';
        $jobType = ''; $jobPosted = ''; $experienceLevel = ''; $salaryMin = ''; $salaryMax = ''; $workArrangement = '';
        $sort_by = 'newest'; $view_mode = 'grid'; $current_page = 1; $per_page = 24; $total_jobs = 5;
        $savedJobIds = [];
        $job_type_counts = ['full-time' => 5];
        $industry_counts = [(object) ['id' => 1, 'name' => 'Technology', 'job_count' => 5]];
        $industries = [(object) ['id' => 1, 'name' => 'Technology']];
        $categories = [(object) ['id' => 1, 'name' => 'Engineering']];
        $states = [(object) ['id' => 25, 'name' => 'Lagos']];

        // 5 distinct test scenarios:
        $jobs = [
            // Job 1: Verified employer with logo
            (object) [
                'id' => 1,
                'title' => 'Senior SRE at Moniepoint',
                'slug' => 'senior-sre-moniepoint',
                'employer_name' => 'Moniepoint Inc.',
                'company_logo' => 'https://www.google.com/s2/favicons?domain=moniepoint.com&sz=128',
                'is_verified' => 1,
                'show_trust_badge' => true,
                'is_anonymous' => 0,
                'anonymous' => 0,
                'created_at' => '2026-10-20',
                'location' => 'Lagos',
                'is_featured' => 1,
                'featured_until' => '2099-01-01',
                'job_type' => 'full-time',
                'salary' => 'Negotiable',
                'salary_details' => 'Negotiable',
                'application_method' => 'external',
            ],
            // Job 2: Verified employer without logo (should show initials + verified check)
            (object) [
                'id' => 2,
                'title' => 'Product Designer at Zenith Solutions',
                'slug' => 'product-designer-zenith',
                'employer_name' => 'Zenith Solutions',
                'company_logo' => '',
                'is_verified' => 1,
                'show_trust_badge' => true,
                'is_anonymous' => 0,
                'anonymous' => 0,
                'created_at' => '2026-10-20',
                'location' => 'Abuja',
                'is_featured' => 0,
                'job_type' => 'full-time',
                'salary' => '450000',
                'salary_details' => '₦450,000 / month',
                'application_method' => 'internal',
            ],
            // Job 3: Unverified employer with logo (should show logo, but NO verified check)
            (object) [
                'id' => 3,
                'title' => 'Marketing Associate at EarlyStage',
                'slug' => 'marketing-earlystage',
                'employer_name' => 'EarlyStage Startups',
                'company_logo' => 'https://www.google.com/s2/favicons?domain=earlystage.ng&sz=128',
                'is_verified' => 0,
                'show_trust_badge' => false,
                'is_anonymous' => 0,
                'anonymous' => 0,
                'created_at' => '2026-10-20',
                'location' => 'Lagos',
                'is_featured' => 0,
                'job_type' => 'full-time',
                'salary' => '200000',
                'salary_details' => '₦200,000 / month',
                'application_method' => 'internal',
            ],
            // Job 4: Confidential Employer (is_anonymous = 1, should NOT have logo and should NOT have verified badge)
            (object) [
                'id' => 4,
                'title' => 'Executive Secretary (Confidential)',
                'slug' => 'executive-secretary-confidential',
                'employer_name' => 'Secret Conglomerate',
                'company_logo' => 'https://www.google.com/s2/favicons?domain=secret.com&sz=128',
                'is_verified' => 1,
                'show_trust_badge' => false,
                'is_anonymous' => 1,
                'anonymous' => 1,
                'created_at' => '2026-10-20',
                'location' => 'Lagos',
                'is_featured' => 0,
                'job_type' => 'full-time',
                'salary' => '300000',
                'salary_details' => '₦300,000 / month',
                'application_method' => 'internal',
            ],
            // Job 5: Confidential Employer via anonymous flag
            (object) [
                'id' => 5,
                'title' => 'Operations Manager (Anonymous)',
                'slug' => 'operations-manager-anon',
                'employer_name' => 'Hidden Firm',
                'company_logo' => 'https://www.google.com/s2/favicons?domain=hidden.com&sz=128',
                'is_verified' => 0,
                'show_trust_badge' => false,
                'is_anonymous' => 0,
                'anonymous' => 1,
                'created_at' => '2026-10-20',
                'location' => 'Port Harcourt',
                'is_featured' => 0,
                'job_type' => 'full-time',
                'salary' => '500000',
                'salary_details' => '₦500,000 / month',
                'application_method' => 'internal',
            ],
        ];

        require APPPATH . 'Views/home/jobs.php';

        $html = $this->sections['content'] ?? '';

        echo "Rendered HTML Length: " . strlen($html) . " characters\n";

        $dom = new DOMDocument();
        @$dom->loadHTML('<?xml encoding="utf-8" ?>' . $html, LIBXML_NOERROR);
        $xpath = new DOMXPath($dom);

        $articles = $xpath->query('//article[contains(@class, "job-card")]');
        echo "Found " . $articles->length . " rendered job cards (5 test jobs expected).\n\n";

        $allPassed = true;

        foreach ($articles as $idx => $article) {
            $num = $idx + 1;
            $titleNode = $xpath->query('.//h3[contains(@class, "job-title")]/a', $article)->item(0);
            $title = $titleNode ? trim($titleNode->textContent) : "Job #{$num}";

            $companyNode = $xpath->query('.//span[contains(@class, "job-company-name")]', $article)->item(0);
            $company = $companyNode ? trim($companyNode->textContent) : "Unknown";

            $verifiedBtns = $xpath->query('.//button[contains(@class, "verified-check")]', $article);
            $hasVerified = ($verifiedBtns->length > 0);

            $logoImgs = $xpath->query('.//div[contains(@class, "job-logo")]//img', $article);
            $hasLogoImg = ($logoImgs->length > 0);
            $imgSrc = $hasLogoImg ? $logoImgs->item(0)->getAttribute('src') : null;

            $confidentialSvgs = $xpath->query('.//div[contains(@class, "job-logo is-confidential")]//svg', $article);
            $hasConfidentialIcon = ($confidentialSvgs->length > 0);

            echo "Test Case #{$num}: {$title}\n";
            echo "  Company Name: '{$company}'\n";
            echo "  Has Verified Badge: " . ($hasVerified ? "YES" : "NO") . "\n";
            echo "  Has Logo <img>: " . ($hasLogoImg ? "YES ({$imgSrc})" : "NO") . "\n";
            echo "  Has Confidential Icon: " . ($hasConfidentialIcon ? "YES" : "NO") . "\n";

            if ($num === 1) {
                if ($hasVerified && $hasLogoImg && !$hasConfidentialIcon && $company === 'Moniepoint Inc.') {
                    echo "  -> [PASS] Verified employer with logo rendered correctly!\n\n";
                } else {
                    echo "  -> [FAIL] Expected verified employer with logo.\n\n";
                    $allPassed = false;
                }
            } elseif ($num === 2) {
                if ($hasVerified && !$hasLogoImg && !$hasConfidentialIcon && $company === 'Zenith Solutions') {
                    echo "  -> [PASS] Verified employer without logo shows initials + verified badge!\n\n";
                } else {
                    echo "  -> [FAIL] Expected verified employer initials + verified badge.\n\n";
                    $allPassed = false;
                }
            } elseif ($num === 3) {
                if (!$hasVerified && $hasLogoImg && !$hasConfidentialIcon && $company === 'EarlyStage Startups') {
                    echo "  -> [PASS] Unverified employer shows logo and NO verified badge!\n\n";
                } else {
                    echo "  -> [FAIL] Expected unverified employer with logo and NO verified badge.\n\n";
                    $allPassed = false;
                }
            } elseif ($num === 4 || $num === 5) {
                if (!$hasVerified && !$hasLogoImg && $hasConfidentialIcon && $company === 'Confidential Employer') {
                    echo "  -> [PASS] Confidential job correctly shows 'Confidential Employer', confidential lock icon, and NO company logo or verified badge!\n\n";
                } else {
                    echo "  -> [FAIL] Confidential job confidentiality failed!\n\n";
                    $allPassed = false;
                }
            }
        }

        return $allPassed;
    }
}

$tester = new TestJobsView();
$result = $tester->renderAndVerify();

if ($result) {
    echo "========================================\n";
    echo "ALL 5 TEST SCENARIOS PASSED WITH 100% SUCCESS!\n";
    echo "========================================\n";
    exit(0);
} else {
    echo "========================================\n";
    echo "TEST FAILURES DETECTED!\n";
    echo "========================================\n";
    exit(1);
}
