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
        'job_seeker_id',
        'job_id',
        'created_at'
    ];

    protected $useTimestamps = false; // we manually set created_at

    /**
     * Save a job for the candidate
     */
    public function saveJob(int $candidateId, int $jobId): bool
    {
        // Prevent duplicate saves
        if ($this->isSaved($candidateId, $jobId)) {
            return true;
        }

        return $this->insert([
            'job_seeker_id' => $candidateId,
            'job_id'       => $jobId,
            'created_at'   => date('Y-m-d H:i:s')
        ]);
    }

    /**
     * Check if a job is already saved
     */
    public function isSaved(int $candidateId, int $jobId): bool
    {
        return $this->where([
            'job_seeker_id' => $candidateId,
            'job_id'       => $jobId
        ])->countAllResults() > 0;
    }

    /**
     * Remove a saved job
     */
    public function removeJob(int $candidateId, int $jobId): bool
    {
        return $this->where([
            'job_seeker_id' => $candidateId,
            'job_id'       => $jobId
        ])->delete();
    }

    /**
     * Get all saved jobs for a candidate
     */
    public function getSavedJobs(int $candidateId)
    {
        return $this->where('job_seeker_id', $candidateId)
            ->orderBy('created_at', 'DESC')
            ->findAll();
    }

    /**
     * Count saved jobs
     */
    public function countSavedJobs(int $candidateId): int
    {
        return $this->where('job_seeker_id', $candidateId)->countAllResults();
    }
}
