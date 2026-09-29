<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;

class Employer extends Entity
{
    protected $dates = ['created_at', 'updated_at'];
    protected $casts = ['id' => 'integer', 'user_id' => 'integer', 'state_id' => 'integer'];

    public function getIndustries()
    {
        return model(\App\Models\IndustryModel::class)
            ->select('industries.*')
            ->join('employer_industries', 'employer_industries.industry_id = industries.id')
            ->where('employer_industries.employer_id', $this->id)
            ->findAll();
    }

    public function getState()
    {
        return model(\App\Models\StateModel::class)->find($this->state_id);
    }

    public function getCountry()
    {
        $state = $this->getState();
        return $state ? model(\App\Models\CountryModel::class)->find($state->country_id) : null;
    }

    public function getEmpIndustries(): array
    {
        return model(\App\Models\EmployerIndustryModel::class)
            ->select('industries.name')
            ->join('industries', 'industries.id = employer_industries.industry_id')
            ->where('employer_id', $this->id)
            ->findColumn('name') ?? [];
    }
}
