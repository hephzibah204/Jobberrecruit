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
}
