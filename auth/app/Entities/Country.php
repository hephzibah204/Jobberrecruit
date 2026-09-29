<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Country extends Entity
{
    protected $attributes = [
        'id' => null,
        'name' => null,
        'iso_code' => null,
        'dial_code' => null,
        'currency' => null,
        'currency_code' => null,
        'is_active' => 1,
        'created_at' => null,
        'updated_at' => null
    ];

    protected $dates = ['created_at', 'updated_at'];

    public function activate()
    {
        $this->attributes['is_active'] = 1;
        return $this;
    }

    public function deactivate()
    {
        $this->attributes['is_active'] = 0;
        return $this;
    }
}
