<?php

namespace App\Models;

use App\Entities\PlanEntity;
use CodeIgniter\Model;

class PlanModel extends Model
{
    protected $table = 'plans';
    protected $returnType = PlanEntity::class;
    protected $useTimestamps = true;

    protected $allowedFields = [
        'code',
        'name',
        'base_price',
        'pricing_tiers',
        'billing_type',      // free | subscription
        'plan_type',         // starter | subscription | bundle
        'monthly_job_credits',
        'features',          // JSON: {"featured": true, "network_blast": true, ...}
        'paystack_plan_code',
        'is_active'
    ];

    // Helper to get decoded features. The entity cast already returns
    // an associative array, so just hand it back.
    public function getFeatures($planId)
    {
        $plan = $this->find($planId);
        return $plan && is_array($plan->features) ? $plan->features : [];
    }
}
