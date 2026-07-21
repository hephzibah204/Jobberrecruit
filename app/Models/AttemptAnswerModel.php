<?php

namespace App\Models;

use CodeIgniter\Model;

class AttemptAnswerModel extends Model
{
    protected $table            = 'attempt_answers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'attempt_id', 'question_id', 'selected_option_ids', 'is_correct'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
}
