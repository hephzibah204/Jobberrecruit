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

    // Optional: Helper to get decoded features
    public function getFeatures($planId)
    {
        $plan = $this->find($planId);
        return $plan ? json_decode($plan->features ?? '{}', true) : [];
    }
}
