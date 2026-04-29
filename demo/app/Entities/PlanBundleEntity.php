<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class PlanBundleEntity extends Entity
{
    protected $casts = [
        'id'               => 'integer',
        'job_credits'      => 'integer',
        'price'            => 'float',
        'price_per_credit' => 'float',
        'is_best_value'    => 'boolean',
        'is_active'        => 'boolean',
    ];

    /**
     * Automatically generate slug if not provided
     */
    public function setName(string $name)
    {
        $this->attributes['name'] = $name;

        if (empty($this->attributes['slug'])) {
            $this->attributes['slug'] = url_title($name, '-', true);
        }

        return $this;
    }

    /**
     * Auto-calculate price per credit if not manually set
     */
    public function setPrice($price)
    {
        $this->attributes['price'] = $price;

        if (!empty($this->attributes['job_credits'])) {
            $this->attributes['price_per_credit'] =
                $price / $this->attributes['job_credits'];
        }

        return $this;
    }

    /**
     * Helper: Check if bundle is best value
     */
    public function isBestValue(): bool
    {
        return (bool) $this->attributes['is_best_value'];
    }

    /**
     * Helper: Check if bundle is active
     */
    public function isActive(): bool
    {
        return (bool) $this->attributes['is_active'];
    }
}
