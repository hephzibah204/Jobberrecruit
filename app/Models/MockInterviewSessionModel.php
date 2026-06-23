<?php

namespace App\Models;

use CodeIgniter\Model;

class MockInterviewSessionModel extends Model
{
    protected $table            = 'mock_interview_sessions';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $protectFields    = true;
    protected $allowedFields    = [
        'application_id',
        'user_id',
        'job_title',
        'difficulty',
        'question_pack',
        'interview_mode',
        'webcam_enabled',
        'duration_seconds',
        'overall_score',
        'star_average',
        'transcript_json',
        'evaluation_json',
    ];

    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
