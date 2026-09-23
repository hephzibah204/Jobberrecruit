<?php

namespace App\Models;

use CodeIgniter\Model;

class JobReportModel extends Model
{
    protected $table            = 'job_reports';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $protectFields    = true;
    protected $allowedFields    = ['job_id', 'user_id', 'reason', 'details', 'status'];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';

    /**
     * Get reports with job details
     */
    public function getReportsWithDetails()
    {
        return $this->select('job_reports.*, jobs.title as job_title, employers.company_name')
                    ->join('jobs', 'jobs.id = job_reports.job_id')
                    ->join('employers', 'employers.id = jobs.employer_id')
                    ->orderBy('job_reports.created_at', 'DESC')
                    ->findAll();
    }
}
