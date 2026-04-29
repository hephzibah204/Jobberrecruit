<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PlanEntity extends Entity
{
    protected $casts = [
        'features' => 'json'
    ];

    public function allows(string $feature): bool
    {
        return $this->features[$feature] ?? false;
    }
}
