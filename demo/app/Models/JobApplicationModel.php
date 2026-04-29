<?php

namespace App\Models;

use CodeIgniter\Model;

class JobApplicationModel extends Model
{
    protected $table      = 'job_applications';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\JobApplication::class;

    protected $allowedFields = [
        'job_id',
        'job_seeker_id',
        'first_name',
        'last_name',
        'email',
        'phone',
        'cv_path',
        'cover_letter',
        'availability',
        'salary_expectation',
        'work_eligibility',
        'consent',
        'status',
        'status_message',
        'is_guest',
        'guest_email_sent',
        'created_at'
    ];

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    protected $useTimestamps = true;

    public function countByStatus(): array
    {
        return $this->select('status, COUNT(*) AS total')
            ->groupBy('status')
            ->findAll();
    }

    public function performanceStats(int $year)
    {
        return [
            'daily' => $this->select('MONTH(created_at) m, COUNT(*) total')
                ->where('YEAR(created_at)', $year)
                ->groupBy('MONTH(created_at)')
                ->findAll(),

            'weekly' => $this->select('MONTH(created_at) m, COUNT(*) total')
                ->where('YEAR(created_at)', $year)
                ->whereIn('status', ['reviewed', 'shortlisted'])
                ->groupBy('MONTH(created_at)')
                ->findAll(),

            'monthly' => $this->select('MONTH(created_at) m, COUNT(*) total')
                ->where('YEAR(created_at)', $year)
                ->where('status', 'hired')
                ->groupBy('MONTH(created_at)')
                ->findAll(),
        ];
    }
}
