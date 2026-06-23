<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\JobSeekerIndustry;

class JobSeekerIndustryModel extends Model
{
    protected $table      = 'job_seeker_industries';
    protected $primaryKey = 'id';
    protected $returnType = JobSeekerIndustry::class;
    protected $allowedFields = [
        'job_seeker_id',
        'industry_id',
    ];

    protected $useTimestamps = false;
}
