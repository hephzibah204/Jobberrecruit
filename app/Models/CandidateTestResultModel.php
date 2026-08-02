<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidateTestResultModel extends Model
{
    protected $table            = 'candidate_test_results';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'candidate_id', 'test_id', 'best_score', 'passed', 'attempt_id',
        'show_on_profile', 'is_public', 'achieved_at'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
}
