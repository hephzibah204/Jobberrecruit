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

    /**
     * Get detailed active subscription information for a user including proration metrics.
     */
    public function getActiveSubscriptionDetails(int $userId): array
    {
        $subModel  = model(UserSubscriptionModel::class);
        $planModel = model(PlanModel::class);
        $now       = date('Y-m-d H:i:s');

        $userSubscription = $subModel
            ->where('user_id', $userId)
            ->where('is_active', 1)
            ->where('ends_at >', $now)
            ->orderBy('ends_at', 'DESC')
            ->first();

        if (!$userSubscription) {
            return [
                'has_active'     => false,
                'subscription'   => null,
                'plan'           => null,
                'plan_name'      => '',
                'paid_amount'    => 0.0,
                'starts_at'      => null,
                'ends_at'        => null,
                'total_days'     => 0,
                'days_used'      => 0,
                'days_remaining' => 0,
                'daily_rate'     => 0.0,
                'unused_credit'  => 0.0,
                'duration_label' => '',
            ];
        }

        $subscriptionObj = (object) $userSubscription;
        $plan = $planModel->find($subscriptionObj->plan_id);

        // Find last paid payment for this user to determine exact paid amount
        $paymentModel = model(\App\Models\PaymentModel::class);
        $lastPayment  = $paymentModel
            ->where('user_id', $userId)
            ->where('status', 'paid')
            ->orderBy('paid_at', 'DESC')
            ->first();

        $startsAtTimestamp = strtotime($subscriptionObj->starts_at);
        $endsAtTimestamp   = strtotime($subscriptionObj->ends_at);
        $nowTimestamp      = time();

        $totalSeconds = max(1, $endsAtTimestamp - $startsAtTimestamp);
        $totalDays    = max(1, (int) round($totalSeconds / 86400));

        $elapsedSeconds = max(0, $nowTimestamp - $startsAtTimestamp);
        $daysUsed       = min($totalDays, (int) floor($elapsedSeconds / 86400));
        $daysRemaining  = max(0, $totalDays - $daysUsed);

        // Determine paid amount
        $paidAmount = 0.0;
        if ($lastPayment && isset($lastPayment['amount']) && (float) $lastPayment['amount'] > 0) {
            $paidAmount = (float) $lastPayment['amount'];
        } else {
            // Fallback to plan pricing tier matching total months
            $months = max(1, (int) round($totalDays / 30));
            $tiers  = $plan && $plan->pricing_tiers
                ? (is_string($plan->pricing_tiers) ? json_decode($plan->pricing_tiers, true) : $plan->pricing_tiers)
                : [];
            $paidAmount = (float) ($tiers[$months] ?? ($plan->base_price * $months ?? 18000));
        }

        $dailyRate = $totalDays > 0 ? ($paidAmount / $totalDays) : 0.0;

        // Exact remaining proration ratio based on remaining seconds / total seconds
        $remainingRatio = max(0.0, min(1.0, ($endsAtTimestamp - $nowTimestamp) / $totalSeconds));
        $unusedCredit   = round($paidAmount * $remainingRatio, 2);

        $monthsCount = max(1, (int) round($totalDays / 30));
        $durationLabel = $monthsCount . ' Month' . ($monthsCount > 1 ? 's' : '');

        return [
            'has_active'     => true,
            'subscription'   => $subscriptionObj,
            'plan'           => $plan,
            'plan_name'      => $plan->name ?? 'Business Pro',
            'paid_amount'    => $paidAmount,
            'starts_at'      => $subscriptionObj->starts_at,
            'ends_at'        => $subscriptionObj->ends_at,
            'total_days'     => $totalDays,
            'days_used'      => $daysUsed,
            'days_remaining' => $daysRemaining,
            'daily_rate'     => round($dailyRate, 2),
            'unused_credit'  => $unusedCredit,
            'duration_label' => $durationLabel,
        ];
    }

    /**
     * Calculate upgrade proration for a new plan / duration choice.
     */
    public function calculateUpgradeProration(int $userId, int $newPlanId, int $durationMonths = 1): array
    {
        $activeDetails = $this->getActiveSubscriptionDetails($userId);
        $planModel     = model(PlanModel::class);
        $newPlan       = $planModel->find($newPlanId);

        if (!$newPlan) {
            throw new \RuntimeException('New plan not found');
        }

        $tiers = is_string($newPlan->pricing_tiers)
            ? json_decode($newPlan->pricing_tiers, true)
            : ($newPlan->pricing_tiers ?? []);

        $newPlanPrice = (float) ($tiers[$durationMonths] ?? ($newPlan->base_price * $durationMonths));

        $unusedCredit = $activeDetails['has_active'] ? $activeDetails['unused_credit'] : 0.0;

        $prorationDiscount = min($newPlanPrice, $unusedCredit);
        $netAmountDue      = max(0.0, round($newPlanPrice - $unusedCredit, 2));

        return [
            'has_active_sub'     => $activeDetails['has_active'],
            'active_details'     => $activeDetails,
            'current_sub_name'   => $activeDetails['plan_name'],
            'current_duration'   => $activeDetails['duration_label'],
            'total_days'         => $activeDetails['total_days'],
            'days_used'          => $activeDetails['days_used'],
            'days_remaining'     => $activeDetails['days_remaining'],
            'original_price'     => $activeDetails['paid_amount'],
            'unused_credit'      => $unusedCredit,
            'new_plan_price'     => $newPlanPrice,
            'proration_discount' => $prorationDiscount,
            'net_amount_due'     => $netAmountDue,
            'is_upgrade'         => $activeDetails['has_active'],
        ];
    }
}
