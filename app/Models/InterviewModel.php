<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\Interview;

class InterviewModel extends Model
{
    protected $table            = 'interviews';
    protected $primaryKey       = 'id';
    protected $returnType       = Interview::class;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'employer_id',
        'job_id',
        'application_id',
        'candidate_name',
        'candidate_email',
        'job_title',
        'scheduled_at',
        'duration_minutes',
        'mode',
        'location',
        'notes',
        'view_token',
        'status',
        'responded_at',
    ];
}
