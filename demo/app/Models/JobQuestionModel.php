<?php

namespace App\Models;

use CodeIgniter\Model;

class JobQuestionModel extends Model
{
    protected $table            = 'job_questions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['job_id', 'question_text', 'question_type', 'is_required', 'created_at'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Get questions for a specific job
     */
    public function getByJob(int $jobId)
    {
        return $this->where('job_id', $jobId)->findAll();
    }
}
