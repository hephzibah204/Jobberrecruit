<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class State extends Entity
{
    protected $attributes = [
        'id' => null,
        'name' => null,
        'capital' => null,
        'region' => null,
        'is_active' => 1,
        'created_at' => null,
        'updated_at' => null
    ];

    protected $dates = ['created_at', 'updated_at'];
    protected $casts = [
        'is_active' => 'boolean',
        'id' => 'integer'
    ];

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

    public function getFormattedName()
    {
        return ucwords(strtolower($this->attributes['name']));
    }

    public function hasCapital()
    {
        return !empty($this->attributes['capital']);
    }
}
