<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PricingRule extends Entity
{
    protected $casts = [
        'price' => 'float',
        'is_active' => 'boolean'
    ];
}

