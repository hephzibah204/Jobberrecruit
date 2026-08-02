<?php

namespace App\Models;

use CodeIgniter\Model;

class QuestionOptionModel extends Model
{
    protected $table            = 'question_options';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'question_id', 'body', 'is_correct', 'sort_order'
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
}
