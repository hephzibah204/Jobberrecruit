<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Job extends Entity
{
    protected $dates   = ['created_at', 'updated_at'];
    protected $casts   = [
        'id' => 'integer',
        'employer_id' => 'integer',
        'state_id' => 'integer',
    ];

    public function getState()
    {
        return model(\App\Models\StateModel::class)->find($this->state_id);
    }

    public function getCountry()
    {
        $state = $this->getState();
        return $state ? model(\App\Models\CountryModel::class)->find($state->country_id) : null;
    }
}
