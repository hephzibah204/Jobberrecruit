<?php

namespace App\Services;

use App\Models\PlanModel;
use App\Models\UserSubscriptionModel;
use App\Models\JobCreditWalletModel;
use App\Models\JobCreditTransactionModel;
use CodeIgniter\Database\Exceptions\DatabaseException;

class SubscriptionService
{
    public function activate(int $userId, int $planId)
    {
        $plan = (new PlanModel())->find($planId);

        (new UserSubscriptionModel())->insert([
            'user_id' => $userId,
            'plan_id' => $planId,
            'starts_at' => date('Y-m-d H:i:s'),
            'ends_at' => date('Y-m-d H:i:s', strtotime('+1 month')),
            'is_active' => 1
        ]);

        (new JobCreditWalletModel())->insert([
            'user_id' => $userId,
            'credits' => $plan->monthly_job_credits,
            'source' => 'subscription'
        ]);
    }

    public function activateFromWebhook(array $data)
    {
        $meta = $data['metadata'];

        $plan = model(PlanModel::class)
            ->where('paystack_plan_code', $meta['plan_code'])
            ->first();

        if (!$plan) return;

        model(UserSubscriptionModel::class)->insert([
            'user_id'  => $meta['user_id'],
            'plan_id'  => $plan->id,
            'starts_at' => date('Y-m-d H:i:s'),
            'ends_at'  => date('Y-m-d H:i:s', strtotime('+1 month')),
            'is_active' => 1
        ]);

        model(JobCreditWalletModel::class)->insert([
            'user_id' => $meta['user_id'],
            'credits' => $plan->monthly_job_credits,
            'source'  => 'subscription'
        ]);
    }

    /**
     * Credit job credits for a subscription cycle
     */
    public function creditMonthly(
        int $userId,
        int $planId,
        string $reference,
        string $source = 'subscription'
    ): void {

        $plan = model(PlanModel::class)->find($planId);

        if (!$plan || (int) $plan->monthly_job_credits <= 0) {
            throw new \RuntimeException('Invalid plan or zero credits');
        }

        $exists = model(JobCreditTransactionModel::class)
            ->where('reference', $reference)
            ->countAllResults();

        if ($exists > 0) {
            return;
        }

        $credits = (int) $plan->monthly_job_credits;

        $db = db_connect();
        $db->transBegin();

        try {

            // 1️⃣ Credit wallet
            model(JobCreditWalletModel::class)->insert([
                'user_id'    => $userId,
                'credits'    => $credits,
                'source'     => $source,
                'expires_at' => date('Y-m-d H:i:s', strtotime('+30 days')),
            ]);

            // 2️⃣ Log transaction
            model(JobCreditTransactionModel::class)->insert([
                'user_id'     => $userId,
                'type'        => 'credit',
                'credits'     => $credits,
                'reference'   => $reference,
                'description' => 'Subscription monthly credits',
                'meta'        => json_encode([
                    'plan_id' => $planId,
                    'source'  => $source
                ])
            ]);

            if ($db->transStatus() === false) {
                throw new DatabaseException('Subscription credit failed');
            }

            $db->transCommit();
        } catch (\Throwable $e) {
            $db->transRollback();
            throw $e;
        }
    }
}
