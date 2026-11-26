<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class OfferLetter extends Entity
{
    protected $casts = [
        'id'               => 'integer',
        'employer_id'      => 'integer',
        'job_id'           => 'integer',
        'application_id'   => 'integer',
        'probation_months' => '?integer',
    ];

    protected $dates = ['created_at', 'updated_at', 'sent_at', 'responded_at'];
}
