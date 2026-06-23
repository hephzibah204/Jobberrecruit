<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use App\Models\UserSubscriptionModel;
use App\Models\SubscriptionPlanModel;
use App\Services\WalletService;

class RenewSubscriptions extends BaseCommand
{
    protected $group = 'Billing';
    protected $name = 'billing:renew-subscriptions';
    protected $description = 'Credits wallets for active subscription renewals';

    public function run(array $params)
    {
        $subModel  = model(UserSubscriptionModel::class);
        $planModel = model(SubscriptionPlanModel::class);
        $wallet    = service('wallet');

        $now = date('Y-m-d H:i:s');

        $subs = $subModel
            ->where('is_active', 1)
            ->where('ends_at <=', $now)
            ->findAll();

        foreach ($subs as $sub) {
            $planId = is_array($sub) ? ($sub['plan_id'] ?? null) : ($sub->plan_id ?? $sub->subscription_plan_id ?? null);
            $plan = $planModel->find($planId);

            if (! $plan) {
                continue;
            }

            $userId = is_array($sub) ? $sub['user_id'] : $sub->user_id;
            $subId = is_array($sub) ? $sub['id'] : $sub->id;

            $wallet->credit(
                $userId,
                $plan->credit_allowance,
                'subscription_credit',
                'SUB-' . time(),
                $subId,
                'Monthly subscription credit'
            );

            $subModel->update($subId, [
                'starts_at' => $now,
                'ends_at'   => date('Y-m-d H:i:s', strtotime('+1 month')),
            ]);
        }
    }
}


// php spark billing:renew-subscriptions
