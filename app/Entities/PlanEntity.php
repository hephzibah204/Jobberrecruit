<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PlanEntity extends Entity
{
    protected $casts = [
        // 'array' param makes JsonCast return an associative array
        // instead of stdClass, so $plan->features['featured'] works.
        'features' => ['json', 'array'],
    ];

    public function allows(string $feature): bool
    {
        return $this->features[$feature] ?? false;
    }
}
