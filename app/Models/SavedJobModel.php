<?php

namespace App\Models;

use CodeIgniter\Model;

class SavedJobModel extends Model
{
    protected $table            = 'saved_jobs';
    protected $primaryKey       = 'id';
    protected $returnType       = 'array';
    protected $useAutoIncrement = true;

    protected $allowedFields = [
        'user_id',
        'job_id',
        'created_at'
    ];

    protected $useTimestamps = false; // we manually set created_at

    /**
     * Save a job for the user
     */
    public function saveJob(int $userId, int $jobId): bool
    {
        // Prevent duplicate saves
        if ($this->isSaved($userId, $jobId)) {
            return true;
        }

        return $this->insert([
            'user_id'    => $userId,
            'job_id'     => $jobId,
            'created_at' => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Check if a job is already saved
     */
    public function isSaved(int $userId, int $jobId): bool
    {
        return $this->where([
            'user_id' => $userId,
            'job_id'  => $jobId
        ])->countAllResults() > 0;
    }

    /**
     * Remove a saved job
     */
    public function removeJob(int $userId, int $jobId): bool
    {
        return $this->where([
            'user_id' => $userId,
            'job_id'  => $jobId
        ])->delete();
    }

    /**
     * Get all saved jobs for a user
     */
    public function getSavedJobs(int $userId)
    {
        return $this->where('user_id', $userId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Count saved jobs
     */
    public function countSavedJobs(int $userId): int
    {
        return $this->where('user_id', $userId)->countAllResults();
    }
}
