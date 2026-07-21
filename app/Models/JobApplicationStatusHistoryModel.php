<?php

namespace App\Models;

use CodeIgniter\Model;

class JobApplicationStatusHistoryModel extends Model
{
    protected $table         = 'job_application_status_history';
    protected $primaryKey    = 'id';
    protected $returnType    = 'array';
    protected $useTimestamps = false;
    protected $allowedFields = [
        'application_id',
        'old_status',
        'new_status',
        'changed_by_user_id',
        'message',
        'created_at',
    ];
    protected $useAutoIncrement = true;

    protected $cast = [
        'id'                 => 'int',
        'application_id'     => 'int',
        'changed_by_user_id' => '?int',
    ];

    /**
     * Get all status history rows for an application, most recent first.
     *
     * @return array<int, array<string, mixed>>
     */
    public function getForApplication(int $applicationId): array
    {
        return $this->where('application_id', $applicationId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }
}
