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
        try {
            $hasIndustries = model(\App\Models\JobSeekerIndustryModel::class)->where('job_seeker_id', $this->id)->countAllResults() > 0;
            if ($hasIndustries) $prefFilled++;
        } catch (\Throwable $e) {
            if (!empty($this->industry)) $prefFilled++;
        }
        $points += (int) round(($prefFilled / 4) * 15);

        // 3. Professional Summary (10%)
        if (!empty(trim((string)$this->bio))) {
            $points += 10;
        }

        // 4. Work Experience (20%) - Structured entries
        $expCount = !empty($this->experiences) && is_countable($this->experiences) ? count($this->experiences) : 0;
        if ($expCount === 0) {
            try {
                $expCount = model(\App\Models\JobSeekerExperienceModel::class)->where('job_seeker_id', $this->id)->countAllResults();
            } catch (\Throwable $e) {}
        }
        if ($expCount > 0) {
            $points += 20;
        } elseif (!empty($this->experience_years)) {
            $points += 10; // partial if only experience_years is filled
        }

        // 5. Education (12%) - Structured entries
        $eduCount = !empty($this->educations) && is_countable($this->educations) ? count($this->educations) : 0;
        if ($eduCount === 0) {
            try {
                $eduCount = model(\App\Models\JobSeekerEducationModel::class)->where('job_seeker_id', $this->id)->countAllResults();
            } catch (\Throwable $e) {}
        }
        if ($eduCount > 0) {
            $points += 12;
        } elseif (!empty($this->education_level)) {
            $points += 6; // partial if only education_level is selected
        }

        // 6. Skills (10%)
        if (!empty(trim((string)$this->skills))) {
            $points += 10;
        }

        // 7. Resume (12%)
        if (!empty(trim((string)$this->resume))) {
            $points += 12;
        }

        // 8. Portfolio (3%)
        if (!empty(trim((string)$this->portfolio))) {
            $points += 3;
        }

        // 9. Certificate (2%) - External certs or completed JobberRecruit course certificates
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

        // 10. Language (1%) - Optional
        if (!empty(trim((string)$this->languages))) {
            $points += 1;
        }

        return min(100, $points);
    }

    /**
     * Get structured breakdown of profile completion tasks and points (Total: 100%).
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
        try {
            $hasIndustries = model(\App\Models\JobSeekerIndustryModel::class)->where('job_seeker_id', $this->id)->countAllResults() > 0;
            if ($hasIndustries) $prefFilled++;
        } catch (\Throwable $e) {
            if (!empty($this->industry)) $prefFilled++;
        }
        $prefPoints = (int) round(($prefFilled / 4) * 15);
        $prefDone = $prefFilled >= 3;

        // 3. Work Experience (20%)
        $expCount = !empty($this->experiences) && is_countable($this->experiences) ? count($this->experiences) : 0;
        if ($expCount === 0) {
            try {
                $expCount = model(\App\Models\JobSeekerExperienceModel::class)->where('job_seeker_id', $this->id)->countAllResults();
            } catch (\Throwable $e) {}
        }
        $expPoints = $expCount > 0 ? 20 : (!empty($this->experience_years) ? 10 : 0);
        $expDone = $expCount > 0 || !empty($this->experience_years);

        // 4. Education (12%)
        $eduCount = !empty($this->educations) && is_countable($this->educations) ? count($this->educations) : 0;
        if ($eduCount === 0) {
            try {
                $eduCount = model(\App\Models\JobSeekerEducationModel::class)->where('job_seeker_id', $this->id)->countAllResults();
            } catch (\Throwable $e) {}
        }
        $eduPoints = $eduCount > 0 ? 12 : (!empty($this->education_level) ? 6 : 0);
        $eduDone = $eduCount > 0 || !empty($this->education_level);

        // 5. Resume (12%)
        $hasResume = !empty(trim((string)$this->resume));
        $resumePoints = $hasResume ? 12 : 0;

        // 6. Skills (10%)
        $hasSkills = !empty(trim((string)$this->skills));
        $skillsPoints = $hasSkills ? 10 : 0;

        // 7. Professional Bio (10%)
        $hasBio = !empty(trim((string)$this->bio));
        $bioPoints = $hasBio ? 10 : 0;

        // 8. Portfolio, Certs & Languages (6%)
        $extraPoints = 0;
        if (!empty(trim((string)$this->portfolio))) $extraPoints += 3;
        try {
            $certCount = model(\App\Models\JobSeekerCertificationModel::class)->where('job_seeker_id', $this->id)->countAllResults();
            if ($certCount == 0 && !empty($this->user_id)) {
                $certCount = model(\App\Models\CourseCertificateModel::class)->where('user_id', $this->user_id)->countAllResults();
            }
            if ($certCount > 0) $extraPoints += 2;
        } catch (\Throwable $e) {}
        if (!empty(trim((string)$this->languages))) $extraPoints += 1;
        $extraDone = $extraPoints >= 3;

        return [
            [
                'key'        => 'personal',
                'title'      => 'Personal details added',
                'max_points' => 15,
                'points'     => $personalPoints,
                'done'       => $personalDone,
                'url'        => base_url('candidate/profile/edit'),
            ],
            [
                'key'        => 'preferences',
                'title'      => 'Job preferences set',
                'max_points' => 15,
                'points'     => $prefPoints,
                'done'       => $prefDone,
                'url'        => base_url('candidate/profile/edit'),
            ],
            [
                'key'        => 'experience',
                'title'      => 'Work experience added',
                'max_points' => 20,
                'points'     => $expPoints,
                'done'       => $expDone,
                'url'        => base_url('candidate/profile/edit'),
            ],
            [
                'key'        => 'education',
                'title'      => 'Education history added',
                'max_points' => 12,
                'points'     => $eduPoints,
                'done'       => $eduDone,
                'url'        => base_url('candidate/profile/edit'),
            ],
            [
                'key'        => 'resume',
                'title'      => 'Resume uploaded',
                'max_points' => 12,
                'points'     => $resumePoints,
                'done'       => $hasResume,
                'url'        => base_url('candidate/profile/edit'),
            ],
            [
                'key'        => 'skills',
                'title'      => 'Skills added',
                'max_points' => 10,
                'points'     => $skillsPoints,
                'done'       => $hasSkills,
                'url'        => base_url('candidate/profile/edit'),
            ],
            [
                'key'        => 'bio',
                'title'      => 'Professional summary added',
                'max_points' => 10,
                'points'     => $bioPoints,
                'done'       => $hasBio,
                'url'        => base_url('candidate/profile/edit'),
            ],
            [
                'key'        => 'portfolio_certs',
                'title'      => 'Portfolio & certifications added',
                'max_points' => 6,
                'points'     => $extraPoints,
                'done'       => $extraDone,
                'url'        => base_url('candidate/profile/edit'),
            ],
        ];
    }
}
