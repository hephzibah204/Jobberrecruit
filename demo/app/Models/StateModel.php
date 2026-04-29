<?php

namespace App\Models;

use CodeIgniter\Model;

class StateModel extends Model
{
    protected $table      = 'states';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\State::class;
    protected $allowedFields = ['name', 'capital', 'region', 'is_active'];

    protected $validationRules = [
        'name' => 'required|min_length[2]|max_length[100]|is_unique[states.name,id,{id}]',
    ];

    protected $validationMessages = [
        'name' => [
            'required' => 'Location name is required',
            'min_length' => 'Location name must be at least 2 characters',
            'max_length' => 'Location name cannot exceed 100 characters',
            'is_unique' => 'This location already exists'
        ]
    ];

    protected $useTimestamps = true;
    protected $dateFormat = 'datetime';
    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    public function getStatesWithStats()
    {
        return $this->db->table('states s')
            ->select('s.*, 
                (SELECT COUNT(*) FROM employers WHERE state_id = s.id) as employer_count,
                (SELECT COUNT(*) FROM jobs WHERE state_id = s.id) as job_count')
            ->orderBy('s.name', 'ASC')
            ->get()
            ->getResult();
    }

    public function getActiveStates()
    {
        return $this->where('is_active', 1)
            ->orderBy('name', 'ASC')
            ->findAll();
    }

    public function getRegions()
    {
        return $this->distinct()
            ->select('region')
            ->where('region IS NOT NULL')
            ->where('region !=', '')
            ->orderBy('region', 'ASC')
            ->findAll();
    }
}
