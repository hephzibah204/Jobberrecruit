<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class JobAlert extends Entity
{
    protected $dates = ['created_at', 'updated_at'];

    protected $casts = [
        'id'           => 'integer',
        'job_seeker_id' => 'integer',
        'location_id'  => 'integer',
    ];

    /**
     * Convenience: Get associated JobSeeker
     */
    public function getJobSeeker()
    {
        return model(\App\Models\JobSeekerModel::class)->find($this->job_seeker_id);
    }

    /**
     * Convenience: Get location (state)
     */
    public function getLocation()
    {
        if (!$this->location_id) return null;
        return model(\App\Models\StateModel::class)->find($this->location_id);
    }
}
