<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class CvReview extends Entity
{
    protected $dates = ['created_at', 'updated_at', 'reviewed_at', 'delivered_at'];
    protected $casts = [
        'id'                 => 'integer',
        'user_id'            => 'integer',
        'admin_id'           => 'integer',
        'amount'             => 'float',
        'feedback_delivered' => 'boolean'
    ];
}
