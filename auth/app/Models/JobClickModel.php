<?php

namespace App\Models;

use CodeIgniter\Model;

class JobClickModel extends Model
{
    protected $table = 'job_clicks';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'job_id',
        'user_id',
        'ip_address',
        'user_agent',
        'created_at',
    ];

    protected $useTimestamps = false; // we handle created_at manually
    protected $returnType = 'array';

    /**
     * Log a job click
     *
     * @param int $jobId
     * @param string $method  Application method (kept for caller compatibility, not stored)
     * @param int|null $userId
     */
    public function logClick(int $jobId, string $method = '', ?int $userId = null): bool
    {
        return $this->insert([
            'job_id'     => $jobId,
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent()->getAgentString(),
            'created_at' => date('Y-m-d H:i:s'),
            'user_id'    => $userId,
        ]);
    }

    /**
     * Total clicks for a job
     */
    public function totalClicks(int $jobId): int
    {
        return $this->where('job_id', $jobId)->countAllResults();
    }
}
