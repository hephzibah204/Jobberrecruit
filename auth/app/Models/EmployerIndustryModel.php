<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\EmployerIndustry;

class EmployerIndustryModel extends Model
{
    protected $table      = 'employer_industries';
    protected $primaryKey = 'id';
    protected $returnType = EmployerIndustry::class;
    protected $allowedFields = [
        'employer_id',
        'industry_id',
    ];

    protected $useTimestamps = false;
}
