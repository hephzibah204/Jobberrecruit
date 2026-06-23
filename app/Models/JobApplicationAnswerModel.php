<?php

namespace App\Models;

use CodeIgniter\Model;

class JobApplicationAnswerModel extends Model
{
    protected $table            = 'job_application_answers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $allowedFields    = ['application_id', 'question_id', 'answer_text', 'created_at'];

    // Dates
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    /**
     * Get answers for a specific application
     */
    public function getByApplication(int $applicationId)
    {
        return $this->select('job_application_answers.*, job_questions.question_text, job_questions.question_type')
                    ->join('job_questions', 'job_questions.id = job_application_answers.question_id')
                    ->where('application_id', $applicationId)
                    ->findAll();
    }
}
