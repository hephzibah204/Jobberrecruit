<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Industry extends Entity
{
    protected $attributes = [
        'id' => null,
        'name' => null,
        'slug' => null,
        'parent_id' => null,
        'description' => null,
        'is_active' => 1,
        'created_at' => null,
        'updated_at' => null
    ];

    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'id'        => 'integer',
        'parent_id' => 'integer',
        'is_active' => 'boolean'
    ];

    /**
     * Get direct children industries
     */
    public function getChildren()
    {
        return model(\App\Models\IndustryModel::class)
            ->where('parent_id', $this->id)
            ->orderBy('name')
            ->findAll();
    }

    /**
     * Get parent industry (if any)
     */
    public function getParent()
    {
        if (empty($this->parent_id)) {
            return null;
        }

        return model(\App\Models\IndustryModel::class)->find($this->parent_id);
    }

    /**
     * Convenience: fetch employers attached to this industry
     */
    public function getEmployers()
    {
        return model(\App\Models\EmployerModel::class)
            ->select('employers.*')
            ->join('employer_industries', 'employer_industries.employer_id = employers.id')
            ->where('employer_industries.industry_id', $this->id)
            ->findAll();
    }

    /**
     * Convenience: fetch job seekers attached to this industry
     */
    public function getJobSeekers()
    {
        return model(\App\Models\JobSeekerModel::class)
            ->select('job_seekers.*')
            ->join('job_seeker_industries', 'job_seeker_industries.job_seeker_id = job_seekers.id')
            ->where('job_seeker_industries.industry_id', $this->id)
            ->findAll();
    }

    public function activate()
    {
        $this->attributes['is_active'] = 1;
        return $this;
    }

    public function deactivate()
    {
        $this->attributes['is_active'] = 0;
        return $this;
    }

    public function isParent()
    {
        return $this->parent_id === null;
    }

    public function hasChildren()
    {
        $model = model(\App\Models\IndustryModel::class);
        return $model->where('parent_id', $this->id)->countAllResults() > 0;
    }

    public function getFullPath()
    {
        if ($this->parent_id) {
            $parent = $this->getParent();
            if ($parent) {
                return $parent->name . ' → ' . $this->name;
            }
        }
        return $this->name;
    }
}
