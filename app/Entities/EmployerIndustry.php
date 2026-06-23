<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class EmployerIndustry extends Entity
{
    protected $casts = [
        'id' => 'integer',
        'employer_id' => 'integer',
        'industry_id' => 'integer',
    ];
}
