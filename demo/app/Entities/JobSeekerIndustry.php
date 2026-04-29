<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class JobSeekerIndustry extends Entity
{
    protected $casts = [
        'id' => 'integer',
        'job_seeker_id' => 'integer',
        'industry_id' => 'integer',
    ];
}
