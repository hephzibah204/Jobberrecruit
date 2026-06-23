<?php
/**
 * Generate a valid Schema.org JobPosting JSON-LD array for a single job.
 * 
 * Usage in view:
 *   $schema = \App\Views\partials\schema\job_posting($job);
 *   echo '<script type="application/ld+json">' . json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE | JSON_THROW_ON_ERROR) . '</script>';
 *
 * @param object $job      The job object from the database (with joined employer fields)
 * @param string $baseUrl  The site base URL
 * @return array           The structured data array ready for json_encode()
 */
if (! function_exists('jobPostingSchema')) {
    function jobPostingSchema($job, $baseUrl)
    {
        // --- Employment type mapping ---
        $typeMap = [
            'full_time'  => 'FULL_TIME',
            'full-time'  => 'FULL_TIME',
            'part_time'  => 'PART_TIME',
            'part-time'  => 'PART_TIME',
            'contract'   => 'CONTRACTOR',
            'internship' => 'INTERN',
            'temporary'  => 'TEMPORARY',
        ];
        $employmentType = $typeMap[strtolower($job->job_type ?? '')] ?? 'FULL_TIME';

        // --- Dates (ISO 8601) ---
        $datePosted   = $job->created_at ? date('c', strtotime($job->created_at)) : date('c');
        $validThrough = ! empty($job->expiry_date)
            ? date('Y-m-d\T23:59:59', strtotime($job->expiry_date))
            : date('Y-m-d\T23:59:59', strtotime('+30 days'));

        // --- Description (mandatory) ---
        $description = '';
        if (! empty($job->description)) {
            $description = strip_tags($job->description);
            $description = html_entity_decode($description, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $description = trim(preg_replace('/\s+/', ' ', $description));
        }
        if (empty($description)) {
            $description = "Apply for the {$job->title} position at JobberRecruit. Find details about this job opportunity and submit your application online.";
        }

        // --- Employer ---
        $isAnonymous = ! empty($job->anonymous) || ! empty($job->is_anonymous);
        $orgName     = $isAnonymous ? 'Confidential Employer' : ($job->employer_name ?? 'JobberRecruit');
        $orgUrl      = $isAnonymous ? $baseUrl : ($job->company_website ?? $baseUrl);
        $orgLogo     = $isAnonymous
            ? $baseUrl . '/images/favicon.png'
            : (! empty($job->company_logo)
                ? (str_starts_with($job->company_logo, 'http') ? $job->company_logo : $baseUrl . '/' . ltrim($job->company_logo, '/'))
                : $baseUrl . '/images/favicon.png');

        // --- Location ---
        $locality = ! empty($job->location) ? $job->location : 'Nigeria';

        // --- Salary ---
        $salaryData = null;
        if (! empty($job->salary) && (float)$job->salary > 0) {
            $salaryPeriod = strtoupper(! empty($job->salary_period) ? $job->salary_period : 'MONTH');
            $salaryData = [
                '@type'    => 'MonetaryAmount',
                'currency' => 'NGN',
                'value'    => [
                    '@type'    => 'QuantitativeValue',
                    'minValue' => (float)$job->salary,
                    'maxValue' => (float)(! empty($job->salary_max) ? $job->salary_max : $job->salary),
                    'unitText' => $salaryPeriod,
                ],
            ];
        }

        // --- Build schema ---
        $schema = [
            '@context'          => 'https://schema.org',
            '@type'             => 'JobPosting',
            'title'             => $job->title ?? 'Job Vacancy',
            'description'       => $description,
            'datePosted'        => $datePosted,
            'validThrough'      => $validThrough,
            'employmentType'    => $employmentType,
            'hiringOrganization' => [
                '@type' => 'Organization',
                'name'  => $orgName,
                'sameAs' => $orgUrl,
                'logo'  => $orgLogo,
            ],
            'jobLocation'       => [
                '@type'   => 'Place',
                'address' => [
                    '@type'           => 'PostalAddress',
                    'addressLocality' => $locality,
                    'addressCountry'  => 'NG',
                ],
            ],
            'applicantLocationRequirements' => [
                '@type' => 'Country',
                'name'  => 'Nigeria',
            ],
            'directApply' => true,
        ];

        if ($salaryData !== null) {
            $schema['baseSalary'] = $salaryData;
        }

        return $schema;
    }
}
