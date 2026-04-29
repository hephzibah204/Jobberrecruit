<?php

namespace App\Entities;

use CodeIgniter\Entity\Entity;
use App\Models\SubscriptionPlanModel;

class UserSubscription extends Entity
{
    protected $datamap = [];
    protected $dates   = ['starts_at', 'ends_at', 'created_at'];
    protected $casts   = [
        'user_id'   => 'int',
        'subscription_plan_id'   => 'int',
    ];
}
