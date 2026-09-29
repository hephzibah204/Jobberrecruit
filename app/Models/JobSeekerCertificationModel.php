<?php

namespace App\Models;

use CodeIgniter\Model;

class JobSeekerCertificationModel extends Model
{
    protected $table      = 'job_seeker_certifications';
    protected $primaryKey = 'id';
    protected $returnType = 'object';
    protected $allowedFields = [
        'job_seeker_id',
        'name',
        'issuing_organization',
        'credential_id',
        'credential_url',
        'issue_month',
        'issue_year',
        'does_not_expire',
        'expiry_month',
        'expiry_year',
        'sort_order',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    public function forSeeker(int $jobSeekerId): array
    {
        return $this->where('job_seeker_id', $jobSeekerId)
            ->orderBy('sort_order', 'ASC')
            ->orderBy('issue_year', 'DESC')
            ->findAll();
    }
}
