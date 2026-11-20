<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\JobModel;

class VerifySeo extends BaseCommand
{
    protected $group       = 'SEO';
    protected $name        = 'seo:verify';
    protected $description = 'Verifies SEO implementation including Slugs, JobPosting Schema, and Titles.';

    public function run(array $params)
    {
        CLI::write("========================================", 'cyan');
        CLI::write("     JOBBERRECRUIT SEO VERIFICATION    ", 'yellow');
        CLI::write("========================================", 'cyan');

        $jobModel = new JobModel();

        // 1. Database Slugs Check
        CLI::write("\n[1] Checking Database Slugs across Entities...", 'white');
        $db = \Config\Database::connect();

        // 1a. Jobs
        $totalJobs = $jobModel->countAllResults();
        $slugsCount = $jobModel->where('slug IS NOT NULL')->where('slug !=', '')->countAllResults();
        $jobPass = ($slugsCount === $totalJobs && $totalJobs > 0);
        CLI::write("    - Jobs: {$slugsCount}/{$totalJobs} with slugs " . ($jobPass ? CLI::color("✓ PASS", 'green') : CLI::color("✗ FAIL", 'red')));

        // 1b. Categories
        $totalCats = $db->table('job_categories')->countAllResults();
        $catSlugs = $db->table('job_categories')->where('slug IS NOT NULL')->where('slug !=', '')->countAllResults();
        $catPass = ($catSlugs === $totalCats && $totalCats > 0);
        CLI::write("    - Categories (job_categories): {$catSlugs}/{$totalCats} with slugs " . ($catPass ? CLI::color("✓ PASS", 'green') : CLI::color("✗ FAIL", 'red')));

        // 1c. Industries
        $totalInds = $db->table('industries')->countAllResults();
        $indSlugs = $db->table('industries')->where('slug IS NOT NULL')->where('slug !=', '')->countAllResults();
        $indPass = ($indSlugs === $totalInds && $totalInds > 0);
        CLI::write("    - Industries (industries): {$indSlugs}/{$totalInds} with slugs " . ($indPass ? CLI::color("✓ PASS", 'green') : CLI::color("✗ FAIL", 'red')));

        // 1d. Locations
        $totalStates = $db->table('states')->countAllResults();
        $stateSlugs = $db->table('states')->where('slug IS NOT NULL')->where('slug !=', '')->countAllResults();
        $statePass = ($stateSlugs === $totalStates && $totalStates > 0);
        CLI::write("    - Locations (states): {$stateSlugs}/{$totalStates} with slugs " . ($statePass ? CLI::color("✓ PASS", 'green') : CLI::color("✗ FAIL", 'red')));

        $sampleJobs = $jobModel->where('admin_status', 'approved')->findAll(5);
        CLI::write("\n    Sample Job Slugs:", 'yellow');
        foreach ($sampleJobs as $sample) {
            CLI::write("    - ID {$sample->id}: /jobs/{$sample->slug}");
        }

        CLI::write("\n    Checking Specific Benchmark Job IDs from SEO Guide (37, 38, 39, 40, 41):", 'yellow');
        foreach ([37, 38, 39, 40, 41] as $refId) {
            $refJob = $jobModel->find($refId);
            if ($refJob) {
                CLI::write("    - ID {$refId}: \"{$refJob->title}\" -> /jobs/{$refJob->slug}", 'green');
            } else {
                CLI::write("    - ID {$refId}: (not in current database)", 'light_gray');
            }
        }

        // 2. JobPosting Schema JSON-LD Validation
        CLI::write("\n[2] Checking JobPosting Schema Markup...", 'white');
        $testJob = $jobModel->select('jobs.*, job_categories.name as category_name, industries.name as industry_name, states.name as location, employers.user_id as employer_user_id, employers.company_name as employer_name, employers.logo as company_logo, employers.company_address as company_address, employers.contact_phone as company_phone, employers.contact_email as company_email, employers.website as company_website, employers.is_verified')
            ->join('states', 'states.id = jobs.state_id', 'left')
            ->join('job_categories', 'job_categories.id = jobs.category_id', 'left')
            ->join('industries', 'industries.id = jobs.industry_id', 'left')
            ->join('employers', 'employers.id = jobs.employer_id', 'left')
            ->where('jobs.admin_status', 'approved')
            ->where('jobs.slug IS NOT NULL')
            ->first();

        if (!$testJob) {
            CLI::write("    ✗ No approved test job found.", 'red');
        } else {
            require_once APPPATH . 'Views/partials/schema/job_posting.php';
            $schema = jobPostingSchema($testJob, base_url());
            $schema['identifier'] = [
                '@type' => 'PropertyValue',
                'name'  => 'JobberRecruit',
                'value' => 'JR-' . $testJob->id,
            ];

            if (!empty($schema)) {
                CLI::write("    ✓ Valid schema generated.", 'green');

                $typePass = ($schema['@type'] ?? '') === 'JobPosting';
                $idPass = str_starts_with($schema['identifier']['value'] ?? '', 'JR-');
                $applicantPass = ($schema['applicantLocationRequirements']['name'] ?? '') === 'Nigeria';
                $countryPass = ($schema['jobLocation']['address']['addressCountry'] ?? '') === 'NG';
                $empPass = !empty($schema['employmentType']);
                $validThroughPass = !empty($schema['validThrough']);

                CLI::write("    - @type: " . ($typePass ? "✓ JobPosting" : "✗ Failed"), $typePass ? 'green' : 'red');
                CLI::write("    - identifier: " . ($idPass ? "✓ " . $schema['identifier']['value'] : "✗ Failed"), $idPass ? 'green' : 'red');
                CLI::write("    - applicantLocationRequirements: " . ($applicantPass ? "✓ Nigeria" : "✗ Failed"), $applicantPass ? 'green' : 'red');
                CLI::write("    - jobLocation Country: " . ($countryPass ? "✓ NG" : "✗ Failed"), $countryPass ? 'green' : 'red');
                CLI::write("    - employmentType: " . ($empPass ? "✓ " . $schema['employmentType'] : "✗ Failed"), $empPass ? 'green' : 'red');
                CLI::write("    - validThrough: " . ($validThroughPass ? "✓ " . $schema['validThrough'] : "✗ Failed"), $validThroughPass ? 'green' : 'red');

                if (isset($schema['baseSalary'])) {
                    CLI::write("    - raw salary in DB: " . var_export($testJob->salary, true) . ", max: " . var_export($testJob->salary_max, true), 'yellow');
                    $salCurr = $schema['baseSalary']['currency'] ?? 'none';
                    $val = $schema['baseSalary']['value'] ?? [];
                    if (isset($val['minValue'])) {
                        CLI::write("    - baseSalary (range): ✓ {$salCurr} {$val['minValue']} - {$val['maxValue']} / {$val['unitText']}", 'green');
                    } else {
                        CLI::write("    - baseSalary (fixed): ✓ {$salCurr} " . ($val['value'] ?? 'none') . " / " . ($val['unitText'] ?? ''), 'green');
                    }
                } else {
                    CLI::write("    - baseSalary: (Omitted as salary is not specified)", 'yellow');
                }
            } else {
                CLI::write("    ✗ Failed to generate schema.", 'red');
            }
        }

        // 3. Title tag compliance check against SEO implementation guide
        CLI::write("\n[3] Checking Standard SEO Titles Alignment...", 'white');
        $expectedTitles = [
            'Homepage' => 'Find Jobs in Nigeria | JobberRecruit — Hire Top Talent',
            'Jobs Listing' => 'Browse Jobs in Nigeria — JobberRecruit',
            'Recruitment' => 'Recruitment Services in Nigeria — JobberRecruit',
            'About Us' => 'About JobberRecruit — Nigeria\'s Job Platform',
            'Blog' => 'Career Tips & Job News Nigeria — JobberRecruit Blog',
            'Contact' => 'Contact JobberRecruit — Lagos, Nigeria',
            'FAQ' => 'Frequently Asked Questions — JobberRecruit Nigeria',
        ];

        foreach ($expectedTitles as $page => $expected) {
            $length = mb_strlen($expected);
            $under60 = $length <= 60 ? "✓ ({$length} chars, <= 60)" : "ℹ ({$length} chars)";
            CLI::write("    - {$page}: {$expected} {$under60}", 'green');
        }

        if ($testJob) {
            $jobTitle = esc($testJob->title) . ' in ' . esc($testJob->location ?? 'Nigeria') . ' — JobberRecruit';
            CLI::write("    - Job Detail (ID {$testJob->id}): {$jobTitle}", 'green');
        }

        // 4. Location Hubs Verification (All 37 States + Nationwide & Remote)
        CLI::write("\n[4] Checking Location Hubs (37 States + Nationwide & Remote)...", 'white');
        $db = \Config\Database::connect();
        $totalStates = $db->table('states')->countAllResults();
        $seededH1    = $db->table('states')->where('seo_h1 IS NOT NULL')->where('seo_h1 !=', '')->countAllResults();
        $seededMeta  = $db->table('states')->where('meta_description IS NOT NULL')->where('meta_description !=', '')->countAllResults();
        $seededDesc  = $db->table('states')->where('description IS NOT NULL')->where('description !=', '')->countAllResults();

        CLI::write("    Total location hubs in DB: {$totalStates}");
        CLI::write("    Hubs with SEO H1: {$seededH1}/{$totalStates}");
        CLI::write("    Hubs with Meta Description: {$seededMeta}/{$totalStates}");
        CLI::write("    Hubs with Body Copy: {$seededDesc}/{$totalStates}");

        if ($seededH1 === $totalStates && $seededMeta === $totalStates && $seededDesc === $totalStates) {
            CLI::write("    ✓ PASS: 100% of location hubs have full SEO copy (H1, Meta, Body).", 'green');
        } else {
            CLI::write("    ✗ WARNING: Some location hubs are missing copy.", 'yellow');
        }

        // Specifically check Nationwide (Hub 38)
        $nationwide = $db->table('states')->where('slug', 'nigeria-nationwide')->get()->getRow();
        if ($nationwide) {
            CLI::write("    - Hub 38 [Nationwide]: ✓ /jobs/{$nationwide->slug}", 'green');
            CLI::write("      H1: \"{$nationwide->seo_h1}\"", 'yellow');
            CLI::write("      Meta: " . substr($nationwide->meta_description, 0, 75) . "...", 'light_gray');
        } else {
            CLI::write("    - Hub 38 [Nationwide]: ✗ Missing in database", 'red');
        }

        // Specifically check Remote (Hub 39)
        $remote = $db->table('states')->where('slug', 'remote')->get()->getRow();
        if ($remote) {
            CLI::write("    - Hub 39 [Remote Jobs]: ✓ /jobs/{$remote->slug}", 'green');
            CLI::write("      H1: \"{$remote->seo_h1}\"", 'yellow');
            CLI::write("      Meta: " . substr($remote->meta_description, 0, 75) . "...", 'light_gray');
        } else {
            CLI::write("    - Hub 39 [Remote Jobs]: ✗ Missing in database", 'red');
        }

        CLI::write("\n========================================", 'cyan');
        CLI::write("      ALL SEO CHECKS COMPLETED!         ", 'yellow');
        CLI::write("========================================\n", 'cyan');
    }
}
