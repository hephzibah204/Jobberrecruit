<?php

namespace App\Services;

class SeoSlugGenerator
{
    /**
     * Known canonical mappings for categories / industries from SEO guide
     */
    private const CANONICAL_INDUSTRY_SLUGS = [
        'accounting, finance & banking'          => 'accounting-finance-banking',
        'accounting & finance'                   => 'accounting-finance-banking',
        'administration & office support'        => 'administration-office-support',
        'agriculture & agribusiness'             => 'agriculture-agribusiness',
        'agriculture & farming'                  => 'agriculture-farming',
        'construction & real estate'             => 'construction-real-estate',
        'customer service & call center'         => 'customer-service-call-center',
        'customer service'                       => 'customer-service',
        'education & training'                   => 'education-training',
        'energy, oil & gas'                      => 'energy-oil-gas',
        'oil & gas'                              => 'energy-oil-gas',
        'engineering & technical'                => 'engineering-technical',
        'engineering'                            => 'engineering',
        'graduate trainee, nysc & internships'   => 'graduate-trainee-nysc-internships',
        'graduate trainee'                       => 'graduate-trainee-nysc-internships',
        'healthcare & pharmaceuticals'           => 'healthcare-pharmaceuticals',
        'healthcare & medical'                   => 'healthcare-medical',
        'hospitality, travel & tourism'          => 'hospitality-travel-tourism',
        'hospitality & tourism'                  => 'hospitality-tourism',
        'human resources & recruitment'          => 'human-resources-recruitment',
        'human resources'                        => 'human-resources',
        'information technology (it) & software' => 'information-technology-software',
        'information technology & software'      => 'information-technology-software',
        'information technology'                 => 'information-technology',
        'legal & compliance'                     => 'legal-compliance',
        'legal'                                  => 'legal',
        'logistics, supply chain & procurement'  => 'logistics-supply-chain-procurement',
        'logistics & transportation'             => 'logistics-transportation',
        'manufacturing & production'             => 'manufacturing-production',
        'marketing, advertising & communications'=> 'marketing-advertising-communications',
        'marketing & sales'                      => 'marketing-sales',
        'media, creative & design'               => 'media-creative-design',
        'media & communications'                 => 'media-communications',
        'ngo & non-profit'                       => 'ngo-non-profit',
        'ngo/charity'                            => 'ngo-charity',
        'procurement & supply chain'             => 'procurement-supply-chain',
        'sales & business development'           => 'sales-business-development',
        'science & research'                     => 'science-research',
        'security & safety'                      => 'security-safety',
    ];

    /**
     * Populate slugs for jobs, categories, industries, and locations.
     *
     * @param bool $force If true, re-evaluates all slugs even if already set
     * @return array Summary of processed items
     */
    public static function populateAll(bool $force = false): array
    {
        $db = \Config\Database::connect();

        $stats = [
            'locations'  => ['total' => 0, 'updated' => 0, 'empty_before' => 0],
            'categories' => ['total' => 0, 'updated' => 0, 'empty_before' => 0],
            'industries' => ['total' => 0, 'updated' => 0, 'empty_before' => 0],
            'jobs'       => ['total' => 0, 'updated' => 0, 'empty_before' => 0],
        ];

        // 1. LOCATIONS (states table)
        $states = $db->table('states')->get()->getResultArray();
        $stats['locations']['total'] = count($states);
        foreach ($states as $state) {
            $currentSlug = trim($state['slug'] ?? '');
            if (empty($currentSlug)) {
                $stats['locations']['empty_before']++;
            }

            if ($force || empty($currentSlug)) {
                $slug = self::generateLocationSlug($state['name'] ?? '');

                if ($slug !== $currentSlug) {
                    $db->table('states')->where('id', $state['id'])->update(['slug' => $slug]);
                    $stats['locations']['updated']++;
                }
            }
        }

        // 2. CATEGORIES (job_categories table)
        $categories = $db->table('job_categories')->get()->getResultArray();
        $stats['categories']['total'] = count($categories);
        foreach ($categories as $cat) {
            $currentSlug = trim($cat['slug'] ?? '');
            if (empty($currentSlug)) {
                $stats['categories']['empty_before']++;
            }

            if ($force || empty($currentSlug)) {
                $slug = self::generateCategorySlug($cat['name'] ?? '', 'job_categories', (int)$cat['id'], $db);

                if ($slug !== $currentSlug) {
                    $db->table('job_categories')->where('id', $cat['id'])->update(['slug' => $slug]);
                    $stats['categories']['updated']++;
                }
            }
        }

        // 3. INDUSTRIES (industries table)
        $industries = $db->table('industries')->get()->getResultArray();
        $stats['industries']['total'] = count($industries);
        foreach ($industries as $ind) {
            $currentSlug = trim($ind['slug'] ?? '');
            if (empty($currentSlug)) {
                $stats['industries']['empty_before']++;
            }

            if ($force || empty($currentSlug)) {
                $slug = self::generateCategorySlug($ind['name'] ?? '', 'industries', (int)$ind['id'], $db);

                if ($slug !== $currentSlug) {
                    $db->table('industries')->where('id', $ind['id'])->update(['slug' => $slug]);
                    $stats['industries']['updated']++;
                }
            }
        }

        // 4. JOBS (jobs table)
        $jobModel = new \App\Models\JobModel();
        $jobs = $jobModel->findAll();
        $stats['jobs']['total'] = count($jobs);
        foreach ($jobs as $job) {
            $currentSlug = trim($job->slug ?? '');
            if (empty($currentSlug)) {
                $stats['jobs']['empty_before']++;
            }

            if ($force || empty($currentSlug)) {
                // Updating title and state_id triggers JobModel beforeUpdate hook
                $jobModel->update($job->id, [
                    'title'    => $job->title,
                    'state_id' => $job->state_id
                ]);
                $stats['jobs']['updated']++;
            }
        }

        return $stats;
    }

    /**
     * Generate canonical location slug
     */
    public static function generateLocationSlug(string $name): string
    {
        $nameLower = strtolower(trim($name));

        if (str_contains($nameLower, 'remote')) {
            return 'remote';
        }

        if (str_contains($nameLower, 'nationwide')) {
            return 'nigeria-nationwide';
        }

        if (str_contains($nameLower, 'fct') || str_contains($nameLower, 'abuja')) {
            return 'fct-abuja';
        }

        $clean = trim(preg_replace('/\s+state$/i', '', $nameLower));
        $clean = preg_replace('/[^a-z0-9\-]+/', '-', $clean);
        $clean = trim($clean, '-');

        return $clean . '-state';
    }

    /**
     * Generate canonical category or industry slug
     */
    public static function generateCategorySlug(string $name, string $table, int $id, $db): string
    {
        $nameLower = strtolower(trim($name));

        if (isset(self::CANONICAL_INDUSTRY_SLUGS[$nameLower])) {
            $baseSlug = self::CANONICAL_INDUSTRY_SLUGS[$nameLower];
        } else {
            // Clean non-alphanumeric chars
            $clean = str_replace(['&', '/', '\\', '(', ')', ',', '.', "'", '"'], '', $nameLower);
            $baseSlug = preg_replace('/\s+/', '-', trim($clean));
            $baseSlug = preg_replace('/[^a-z0-9\-]+/', '', $baseSlug);
            $baseSlug = preg_replace('/-+/', '-', $baseSlug);
            $baseSlug = trim($baseSlug, '-');

            if (empty($baseSlug)) {
                $baseSlug = 'item-' . $id;
            }
        }

        // Ensure uniqueness
        $slug = $baseSlug;
        $counter = 1;
        while ($db->table($table)->where('slug', $slug)->where('id !=', $id)->countAllResults() > 0) {
            $counter++;
            $slug = "{$baseSlug}-{$counter}";
        }

        return $slug;
    }
}
