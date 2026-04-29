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
            ->where('status', 'active')
            ->where('ends_at <=', $now)
            ->findAll();

        foreach ($subs as $sub) {
            $plan = $planModel->find($sub->subscription_plan_id);

            if (! $plan) {
                continue;
            }

            $wallet->credit(
                $sub->user_id,
                $plan->credit_allowance,
                'subscription_credit',
                'SUB-' . time(),
                $sub->id,
                'Monthly subscription credit'
            );

            $subModel->update($sub->id, [
                'starts_at' => $now,
                'ends_at'   => date('Y-m-d H:i:s', strtotime('+1 month')),
            ]);
        }
    }
}


// php spark billing:renew-subscriptions
