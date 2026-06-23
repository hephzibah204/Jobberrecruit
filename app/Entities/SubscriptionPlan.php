<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class SubscriptionPlan extends Entity
{
    protected $casts = [
        'price' => 'float',
        'credit_allowance' => 'float',
        'discount_percentage' => 'integer'
    ];
}
