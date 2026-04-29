<?php

namespace App\Models;

use CodeIgniter\Model;

class ApplicationAnswerModel extends Model
{
    protected $table            = 'application_answers';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['application_id', 'question_id', 'answer', 'created_at'];

    protected $useTimestamps = false;
}
