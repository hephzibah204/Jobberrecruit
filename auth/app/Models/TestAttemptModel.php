<?php

namespace App\Models;

use CodeIgniter\Model;

class TestAttemptModel extends Model
{
    protected $table            = 'test_attempts';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'candidate_id', 'test_id', 'mode', 'status', 'score_pct', 'passed',
        'num_correct', 'num_total', 'started_at', 'expires_at', 'submitted_at',
        'question_ids', 'flagged', 'employer_required', 'job_id'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
}
