<?php

namespace App\Models;

use CodeIgniter\Model;

class JobSeekerEducationModel extends Model
{
    protected $table      = 'job_seeker_education';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'job_seeker_id',
        'degree',
        'field_of_study',
        'school',
        'start_year',
        'end_year',
        'grade',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function forSeeker(int $jobSeekerId): array
    {
        return $this->where('job_seeker_id', $jobSeekerId)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('end_year', 'DESC')
            ->findAll();
    }
}
