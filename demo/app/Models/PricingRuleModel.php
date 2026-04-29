<?php

namespace App\Models;
use App\Entities\PricingRule;

use CodeIgniter\Model;

class PricingRuleModel extends Model
{
    protected $table = 'pricing_rules';
    protected $primaryKey = 'id';
    protected $returnType = PricingRule::class;
    protected $allowedFields = [
        'plan_id',
        'bundle_id', // nullable
        'action', // post_job
        'price',
        'currency',
        'is_active'
    ];

    protected $useTimestamps = true;
}
