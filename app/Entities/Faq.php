<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Faq extends Entity
{
    protected $casts = [
        'id'         => 'integer',
        'sort_order' => 'integer',
        'is_active'  => 'integer',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
