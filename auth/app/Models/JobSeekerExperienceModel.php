<?php

namespace App\Models;

use CodeIgniter\Model;

class JobSeekerExperienceModel extends Model
{
    protected $table      = 'job_seeker_experiences';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'job_seeker_id',
        'job_title',
        'company',
        'location',
        'start_date',
        'end_date',
        'is_current',
        'description',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function forSeeker(int $jobSeekerId): array
    {
        return $this->where('job_seeker_id', $jobSeekerId)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('start_date', 'DESC')
            ->findAll();
    }
}
