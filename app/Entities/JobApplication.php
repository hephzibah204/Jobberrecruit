<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class JobApplication extends Entity
{
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'id' => 'integer',
        'job_id' => 'integer',
        'job_seeker_id' => 'integer',
    ];
}
