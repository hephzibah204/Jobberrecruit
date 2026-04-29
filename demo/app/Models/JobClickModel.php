<?php

namespace App\Models;

use CodeIgniter\Model;

class JobClickModel extends Model
{
    protected $table = 'job_clicks';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'job_id',
        'method',
        'ip_address',
        'user_agent',
        'referrer',
        'created_at',
        'user_id',
    ];

    protected $useTimestamps = false; // we handle created_at manually
    protected $returnType = 'array';

    /**
     * Log a job click
     *
     * @param int $jobId
     * @param string $method
     * @param string|null $userId
     */
    public function logClick(int $jobId, string $method, ?int $userId = null): bool
    {
        return $this->insert([
            'job_id'     => $jobId,
            'method'     => $method,
            'ip_address' => service('request')->getIPAddress(),
            'user_agent' => service('request')->getUserAgent()->getAgentString(),
            'referrer'   => service('request')->getHeaderLine('Referer'),
            'created_at' => date('Y-m-d H:i:s'),
            'user_id'    => $userId,
        ]);
    }

    /**
     * Count clicks by method
     */
    public function countByMethod(int $jobId): array
    {
        return $this->select('method, COUNT(*) as total')
            ->where('job_id', $jobId)
            ->groupBy('method')
            ->findAll();
    }

    /**
     * Total clicks for a job
     */
    public function totalClicks(int $jobId): int
    {
        return $this->where('job_id', $jobId)->countAllResults();
    }
}
