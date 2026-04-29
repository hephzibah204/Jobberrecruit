<?php

namespace App\Models;

use CodeIgniter\Model;

class SubscriptionPlanModel extends Model
{
    protected $table = 'subscription_plans';
    protected $primaryKey = 'id';
    protected $returnType = \App\Entities\SubscriptionPlan::class;

    protected $allowedFields = [
        'name',
        'price',
        'billing_cycle',
        'credit_allowance',
        'discount_percentage',
        'is_active'
    ];
    // protected $allowedFields = [
    //     'name',
    //     'slug',
    //     'price',
    //     'duration',
    //     'job_limit',
    //     'featured_limit',
    //     'search_access',
    //     'description',
    //     'paystack_plan_code',
    // ];

    protected $createdField = 'created_at';
    protected $updatedField = 'updated_at';

    // protected $returnType = \App\Entities\SubscriptionPlan::class;
}
