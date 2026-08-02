<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidateAlertModel extends Model
{
    protected $table      = 'candidate_alerts';
    protected $primaryKey = 'id';
    protected $returnType = 'array';

    protected $allowedFields = [
        'employer_id',
        'name',
        'criteria',
        'frequency',
        'email_active',
        'active',
        'last_sent_at',
    ];

    protected $useTimestamps = true;

    /**
     * Get all alerts for an employer, newest first.
     */
    public function forEmployer(int $employerId): array
    {
        return $this->where('employer_id', $employerId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
