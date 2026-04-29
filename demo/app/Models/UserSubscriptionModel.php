<?php

namespace App\Models;

use CodeIgniter\Model;

class UserSubscriptionModel extends Model
{
    protected $table = 'user_subscriptions';
    protected $useTimestamps = true;

    protected $allowedFields = [
        'user_id',
        'plan_id',
        'starts_at',
        'ends_at',
        'is_active',
        'auto_renew'
    ];

    public function getActiveSubscription(int $userId)
    {
        return $this->where('user_id', $userId)
            ->where('is_active', 1)
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->orderBy('ends_at', 'DESC')
            ->first();
    }
}
