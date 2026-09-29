<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class JobSeeker extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'state_id' => 'integer'];

    public function getIndustries()
    {
        return model(\App\Models\IndustryModel::class)
            ->select('industries.*')
            ->join('job_seeker_industries', 'job_seeker_industries.industry_id = industries.id')
            ->where('job_seeker_industries.job_seeker_id', $this->id)
            ->findAll();
    }

    public function getCategories()
    {
        return model(\App\Models\JobCategoryModel::class)
            ->select('job_categories.*')
            ->join('job_seeker_categories', 'job_seeker_categories.category_id = job_categories.id')
            ->where('job_seeker_categories.job_seeker_id', $this->id)
            ->findAll();
    }

    public function getState()
    {
        return model(\App\Models\StateModel::class)->find($this->state_id);
    }

    public function getCountry()
    {
        $state = $this->getState();
        return $state ? model(\App\Models\CountryModel::class)->find($state->country_id) : null;
    }

    public function getJobAlerts()
    {
        return model(\App\Models\JobAlertModel::class)
            ->where('job_seeker_id', $this->id)
            ->findAll();
    }

    /**
     * Fetch jobs that match this seeker's preferences
     */
    public function getMatchingJobs()
    {
        $jobModel = model(\App\Models\JobModel::class);

        // Categories
        $categories = $this->getCategories();
        $categoryIds = array_column($categories, 'id');

        // Alerts
        $alerts = $this->getJobAlerts();

        // Base query
        $builder = $jobModel->select('jobs.*')
            ->where('jobs.status', 'open');

        // Filter by categories (if any)
        if (!empty($categoryIds)) {
            $builder->whereIn('jobs.category_id', $categoryIds);
        }

        // Apply alerts
        foreach ($alerts as $alert) {
            if (!empty($alert->keyword)) {
                $builder->like('jobs.title', $alert->keyword);
            }

            if (!empty($alert->location_id)) {
                $builder->where('jobs.state_id', $alert->location_id);
            }
        }

        return $builder->findAll();
    }

    public function getCertifications(): array
    {
        if (isset($this->attributes['certifications']) && is_array($this->attributes['certifications'])) {
            return $this->attributes['certifications'];
        }
        try {
            return model(\App\Models\JobSeekerCertificationModel::class)->forSeeker((int) $this->id);
        } catch (\Throwable $e) {
            return [];
        }
    }

    /**
     * Calculate profile completion percentage based on exact allocation:
     * - Personal Information: 15%
     * - Job Preference: 15%
     * - Professional Summary: 10%
     * - Work Experience: 20%
     * - Education: 12%
     * - Skills: 10%
     * - Resume: 12%
     * - Portfolio: 3%
     * - Certificate: 2%
     * - Language: 1%
     * Total = 100%
     */
    public function getProfileCompletion(): int
    {
        if (empty($this->id)) {
            return 0;
        }

        $points = 0;

        // 1. Personal Information (15%) - 5 criteria (3% each)
        $personalFilled = 0;
        if (!empty($this->full_name)) $personalFilled++;
        if (!empty($this->phone)) $personalFilled++;
        if (!empty($this->dob)) $personalFilled++;
        if (!empty($this->gender)) $personalFilled++;
        if (!empty($this->state_id) || !empty($this->location)) $personalFilled++;
        $points += (int) round(($personalFilled / 5) * 15);

        // 2. Job Preference (15%) - 4 criteria (~3.75% each)
        $prefFilled = 0;
        if (!empty($this->job_title)) $prefFilled++;
        if (!empty($this->employment_type)) $prefFilled++;
        if (!empty($this->desired_salary) || !empty($this->salary_type) || !empty($this->availability)) $prefFilled++;
        $hasIndustries = false;
        try {
            $hasIndustries = model(\App\Models\JobSeekerIndustryModel::class)->where('job_seeker_id', $this->id)->countAllResults() > 0;
        } catch (\Throwable $e) {}
        if ($hasIndustries || !empty($this->industry)) {
            $prefFilled++;
        }
        $points += (int) round(($prefFilled / 4) * 15);

        // 3. Professional Summary (10%)
        if (!empty(trim((string)$this->bio))) {
            $points += 10;
        }

        // 4. Work Experience (20%) - Strictly requires actual structured entries
        $expCount = !empty($this->experiences) && is_countable($this->experiences) ? count($this->experiences) : 0;
        if ($expCount === 0) {
            try {
                $expCount = model(\App\Models\JobSeekerExperienceModel::class)->where('job_seeker_id', $this->id)->countAllResults();
            } catch (\Throwable $e) {}
        }
        if ($expCount > 0) {
            $points += 20;
        } elseif (!empty($this->experience_years)) {
            $points += 10;
        }

        // 5. Education (12%) - Strictly requires actual structured entries
        $eduCount = !empty($this->educations) && is_countable($this->educations) ? count($this->educations) : 0;
        if ($eduCount === 0) {
            try {
                $eduCount = model(\App\Models\JobSeekerEducationModel::class)->where('job_seeker_id', $this->id)->countAllResults();
            } catch (\Throwable $e) {}
        }
        if ($eduCount > 0) {
            $points += 12;
        } elseif (!empty($this->education_level)) {
            $points += 6;
        }

        // 6. Skills (10%)
        if (!empty(trim((string)$this->skills))) {
            $points += 10;
        }

        // 7. Resume (12%) - Core prominent requirement
        if (!empty(trim((string)$this->resume))) {
            $points += 12;
        }

        // 8. Portfolio (3%) - Optional bonus
        if (!empty(trim((string)$this->portfolio))) {
            $points += 3;
        }

        // 9. Certificate (2%) - External certs or completed JobberRecruit course certificates (Optional bonus)
        $certCount = !empty($this->certifications) && is_countable($this->certifications) ? count($this->certifications) : 0;
        if ($certCount === 0) {
            try {
                $certCount = model(\App\Models\JobSeekerCertificationModel::class)->where('job_seeker_id', $this->id)->countAllResults();
                if ($certCount == 0 && !empty($this->user_id)) {
                    $certCount = model(\App\Models\CourseCertificateModel::class)->where('user_id', $this->user_id)->countAllResults();
                }
            } catch (\Throwable $e) {}
        }
        if ($certCount > 0) {
            $points += 2;
        }

        // 10. Language (1%) - Optional bonus
        if (!empty(trim((string)$this->languages))) {
            $points += 1;
        }

        return min(100, $points);
    }

    /**
     * Get structured breakdown of profile completion tasks and points (Total: 100%).
     * Prominently includes Resume (15%) near the top of the profile-completion structure.
     */
    public function getProfileChecklist(): array
    {
        if (empty($this->id)) {
            return [];
        }

        // 1. Personal Information (15%)
        $personalFilled = 0;
        if (!empty($this->full_name)) $personalFilled++;
        if (!empty($this->phone)) $personalFilled++;
        if (!empty($this->dob)) $personalFilled++;
        if (!empty($this->gender)) $personalFilled++;
        if (!empty($this->state_id) || !empty($this->location)) $personalFilled++;
        $personalPoints = (int) round(($personalFilled / 5) * 15);
        $personalDone = $personalFilled >= 4;

        // 2. Job Preference (15%)
        $prefFilled = 0;
        if (!empty($this->job_title)) $prefFilled++;
        if (!empty($this->employment_type)) $prefFilled++;
        if (!empty($this->desired_salary) || !empty($this->salary_type) || !empty($this->availability)) $prefFilled++;
        $hasIndustries = false;
        try {
            $hasIndustries = model(\App\Models\JobSeekerIndustryModel::class)->where('job_seeker_id', $this->id)->countAllResults() > 0;
        } catch (\Throwable $e) {}
        if ($hasIndustries || !empty($this->industry)) {
            $prefFilled++;
        }
        $prefPoints = (int) round(($prefFilled / 4) * 15);
        $prefDone = $prefFilled >= 3;

        // 3. Resume (12%) - PROMINENT in profile-completion structure
        $hasResume = !empty(trim((string)$this->resume));
        $resumePoints = $hasResume ? 12 : 0;

        // 4. Professional Summary (10%)
        $hasBio = !empty(trim((string)$this->bio));
        $bioPoints = $hasBio ? 10 : 0;

        // 5. Work Experience (20%) - Core required section
        $expCount = !empty($this->experiences) && is_countable($this->experiences) ? count($this->experiences) : 0;
        if ($expCount === 0) {
            try {
                $expCount = model(\App\Models\JobSeekerExperienceModel::class)->where('job_seeker_id', $this->id)->countAllResults();
            } catch (\Throwable $e) {}
        }
        $expDone = $expCount > 0;
        $expPoints = $expDone ? 20 : (!empty($this->experience_years) ? 10 : 0);

        // 6. Education (12%) - Core required section
        $eduCount = !empty($this->educations) && is_countable($this->educations) ? count($this->educations) : 0;
        if ($eduCount === 0) {
            try {
                $eduCount = model(\App\Models\JobSeekerEducationModel::class)->where('job_seeker_id', $this->id)->countAllResults();
            } catch (\Throwable $e) {}
        }
        $eduDone = $eduCount > 0;
        $eduPoints = $eduDone ? 12 : (!empty($this->education_level) ? 6 : 0);

        // 7. Skills (10%)
        $hasSkills = !empty(trim((string)$this->skills));
        $skillsPoints = $hasSkills ? 10 : 0;

        // 8. Portfolio (3%) - Optional
        $hasPortfolio = !empty(trim((string)$this->portfolio));
        $portfolioPoints = $hasPortfolio ? 3 : 0;

        // 9. Certifications (2%) - Optional
        $certCount = !empty($this->certifications) && is_countable($this->certifications) ? count($this->certifications) : 0;
        if ($certCount === 0) {
            try {
                $certCount = model(\App\Models\JobSeekerCertificationModel::class)->where('job_seeker_id', $this->id)->countAllResults();
                if ($certCount == 0 && !empty($this->user_id)) {
                    $certCount = model(\App\Models\CourseCertificateModel::class)->where('user_id', $this->user_id)->countAllResults();
                }
            } catch (\Throwable $e) {}
        }
        $certPoints = $certCount > 0 ? 2 : 0;

        // 10. Languages (1%) - Optional
        $hasLanguages = !empty(trim((string)$this->languages));
        $langPoints = $hasLanguages ? 1 : 0;

        return [
            [
                'key'          => 'personal',
                'title'        => 'Personal Information',
                'max_points'   => 15,
                'points'       => $personalPoints,
                'done'         => $personalDone,
                'optional'     => false,
                'is_prominent' => false,
                'target_id'    => 'sec-personal',
                'url'          => base_url('candidate/profile/edit#sec-personal'),
            ],
            [
                'key'          => 'preferences',
                'title'        => 'Job Preference',
                'max_points'   => 15,
                'points'       => $prefPoints,
                'done'         => $prefDone,
                'optional'     => false,
                'is_prominent' => false,
                'target_id'    => 'sec-preferences',
                'url'          => base_url('candidate/profile/edit#sec-preferences'),
            ],
            [
                'key'          => 'resume',
                'title'        => 'Resume (CV Document)',
                'max_points'   => 12,
                'points'       => $resumePoints,
                'done'         => $hasResume,
                'optional'     => false,
                'is_prominent' => true,
                'target_id'    => 'sec-resume',
                'url'          => base_url('candidate/profile/edit#sec-resume'),
            ],
            [
                'key'          => 'bio',
                'title'        => 'Professional Summary',
                'max_points'   => 10,
                'points'       => $bioPoints,
                'done'         => $hasBio,
                'optional'     => false,
                'is_prominent' => false,
                'target_id'    => 'sec-summary',
                'url'          => base_url('candidate/profile/edit#sec-summary'),
            ],
            [
                'key'          => 'experience',
                'title'        => 'Work Experience',
                'max_points'   => 20,
                'points'       => $expPoints,
                'done'         => $expDone,
                'optional'     => false,
                'is_prominent' => false,
                'target_id'    => 'sec-experience',
                'url'          => base_url('candidate/profile/edit#sec-experience'),
            ],
            [
                'key'          => 'education',
                'title'        => 'Education',
                'max_points'   => 12,
                'points'       => $eduPoints,
                'done'         => $eduDone,
                'optional'     => false,
                'is_prominent' => false,
                'target_id'    => 'sec-education',
                'url'          => base_url('candidate/profile/edit#sec-education'),
            ],
            [
                'key'          => 'skills',
                'title'        => 'Skills',
                'max_points'   => 10,
                'points'       => $skillsPoints,
                'done'         => $hasSkills,
                'optional'     => false,
                'is_prominent' => false,
                'target_id'    => 'sec-skills',
                'url'          => base_url('candidate/profile/edit#sec-skills'),
            ],
            [
                'key'          => 'portfolio',
                'title'        => 'Portfolio & Work Samples',
                'max_points'   => 3,
                'points'       => $portfolioPoints,
                'done'         => $hasPortfolio,
                'optional'     => true,
                'is_prominent' => false,
                'target_id'    => 'sec-portfolio',
                'url'          => base_url('candidate/profile/edit#sec-portfolio'),
            ],
            [
                'key'          => 'certificate',
                'title'        => 'Licences & Certifications',
                'max_points'   => 2,
                'points'       => $certPoints,
                'done'         => $certCount > 0,
                'optional'     => true,
                'is_prominent' => false,
                'target_id'    => 'sec-certifications',
                'url'          => base_url('candidate/profile/edit#sec-certifications'),
            ],
            [
                'key'          => 'language',
                'title'        => 'Languages',
                'max_points'   => 1,
                'points'       => $langPoints,
                'done'         => $hasLanguages,
                'optional'     => true,
                'is_prominent' => false,
                'target_id'    => 'sec-languages',
                'url'          => base_url('candidate/profile/edit#sec-languages'),
            ],
        ];
    }
}
