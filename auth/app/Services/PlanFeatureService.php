<?php

namespace App\Services;

use App\Models\UserSubscriptionModel;

class PlanFeatureService
{
    public function userAllows(int $userId, string $feature): bool
    {
        $subscription = model(UserSubscriptionModel::class)
            ->where('user_id', $userId)
            ->where('is_active', 1)
            ->orderBy('ends_at', 'DESC')
            ->first();

        if ($subscription) {
            return $subscription->getPlan()->allows($feature);
        }

        return false;
    }
}
