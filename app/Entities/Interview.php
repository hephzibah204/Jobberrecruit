<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Interview extends Entity
{
    protected $casts = [
        'id'               => 'integer',
        'employer_id'      => 'integer',
        'job_id'           => 'integer',
        'application_id'   => 'integer',
        'duration_minutes' => 'integer',
    ];

    protected $dates = ['created_at', 'updated_at', 'responded_at'];
}
