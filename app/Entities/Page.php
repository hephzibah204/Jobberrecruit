<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Page extends Entity
{
    protected $casts = [
        'id'        => 'integer',
        'is_active' => 'integer',
    ];

    protected $dates = ['created_at', 'updated_at'];
}
