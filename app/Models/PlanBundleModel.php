<?php

namespace App\Models;

use CodeIgniter\Model;
use App\Entities\PlanBundleEntity;

class PlanBundleModel extends Model
{
    protected $table      = 'plan_bundles';
    protected $primaryKey = 'id';

    protected $returnType = PlanBundleEntity::class;
    protected $useTimestamps = true;

    protected $allowedFields = [
        'name',
        'slug',
        'job_credits',
        'price',
        'price_per_credit',
        'is_best_value',
        'is_active'
    ];
}
