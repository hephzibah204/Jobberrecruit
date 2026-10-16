<?php

namespace App\Models;

use CodeIgniter\Model;

class CandidateShortlistModel extends Model
{
    protected $table            = 'candidate_shortlists';
    protected $primaryKey       = 'id';
    protected $useAutoIncrement = true;
    protected $returnType       = 'object';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [
        'employer_id',
        'user_id',
        'admin_id',
        'job_seeker_id',
        'created_at',
    ];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
}
