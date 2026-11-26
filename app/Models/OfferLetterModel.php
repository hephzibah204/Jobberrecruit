<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\OfferLetter;

class OfferLetterModel extends Model
{
    protected $table            = 'offer_letters';
    protected $primaryKey       = 'id';
    protected $returnType       = OfferLetter::class;
    protected $useTimestamps    = true;

    protected $allowedFields = [
        'employer_id',
        'job_id',
        'application_id',
        'candidate_name',
        'candidate_email',
        'job_title',
        'salary',
        'currency',
        'employment_type',
        'start_date',
        'probation_months',
        'letter_body',
        'view_token',
        'status',
        'sent_at',
        'responded_at',
    ];
}
