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

        if (!$plan) throw new \RuntimeException('Invalid subscription plan');
        // Unlimited plans activate without depositing numeric units.
        if ((new CreditService())->planProvidesUnlimitedPosting($plan)) return;
        if ((int) $plan->monthly_job_credits <= 0) throw new \RuntimeException('Plan has no credit allocation');

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
                'type'        => 'credit_in',
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
            ->where('starts_at <=', $now)
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

        // An unrelated wallet deposit, course, or bundle is never subscription value.
        $payments = model(\App\Models\PaymentModel::class)
            ->where('user_id', $userId)->where('plan_id', $subscriptionObj->plan_id)
            ->where('status', 'paid')->orderBy('paid_at', 'DESC')->findAll();
        $paidAmount = 0.0;
        foreach ($payments as $payment) {
            $value = self::subscriptionPaymentValue($payment, (int) $subscriptionObj->id,
                (int) $subscriptionObj->plan_id, (string) $subscriptionObj->starts_at);
            if ($value !== null) {
                $paidAmount = $value;
                break;
            }
        }

        $startsAtTimestamp = strtotime($subscriptionObj->starts_at);
        $endsAtTimestamp   = strtotime($subscriptionObj->ends_at);
        $nowTimestamp      = time();

        $totalSeconds = max(1, $endsAtTimestamp - $startsAtTimestamp);
        $totalDays    = max(1, (int) round($totalSeconds / 86400));

        $elapsedSeconds = max(0, $nowTimestamp - $startsAtTimestamp);
        $daysUsed       = min($totalDays, (int) floor($elapsedSeconds / 86400));
        $daysRemaining  = max(0, $totalDays - $daysUsed);

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
        $planModel     = model(PlanModel::class);
        $newPlan       = $planModel->find($newPlanId);

        if (!$newPlan) {
            throw new \RuntimeException('New plan not found');
        }

        $tiers = is_string($newPlan->pricing_tiers)
            ? json_decode($newPlan->pricing_tiers, true)
            : ($newPlan->pricing_tiers ?? []);

        $newPlanPrice = self::priceForDuration($durationMonths, (float) $newPlan->base_price, is_array($tiers) ? $tiers : []);
        $activeDetails = $this->getActiveSubscriptionDetails($userId);

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
    public static function priceForDuration(int $months, float $basePrice, array $tiers): float
    {
        if ($months < 1 || $months > 36 || ($tiers && !array_key_exists($months, $tiers)) || (!$tiers && $months !== 1)) {
            throw new \InvalidArgumentException('Please select an available subscription duration.');
        }
        $price = $tiers[$months] ?? $basePrice;
        if (!is_numeric($price) || !is_finite((float) $price) || (float) $price < 0) {
            throw new \InvalidArgumentException('Invalid subscription price.');
        }
        return (float) $price;
    }

    public static function subscriptionPaymentValue(array $payment, int $subscriptionId, int $planId, string $startsAt): ?float
    {
        if ((int) ($payment['plan_id'] ?? 0) !== $planId || ($payment['status'] ?? '') !== 'paid') {
            return null;
        }
        $meta = PaymentFulfillmentService::metadata($payment);
        if (!in_array($meta['type'] ?? '', ['subscription', 'employer_plan', 'candidate_plan'], true)) {
            return null;
        }
        if (!empty($meta['subscription_id'])) {
            if ((int) $meta['subscription_id'] !== $subscriptionId) {
                return null;
            }
        } else {
            // Compatibility for older records without an explicit subscription ID.
            // Only accept a payment made at activation, for this same plan and type.
            $paidAt = strtotime($payment['paid_at'] ?? '');
            $start = strtotime($startsAt);
            if (!$paidAt || !$start || abs($paidAt - $start) > 300) {
                return null;
            }
        }
        $value = $meta['subscription_value'] ?? $meta['proration']['new_plan_price'] ?? $payment['amount'] ?? 0;
        return is_numeric($value) && is_finite((float) $value) && (float) $value >= 0 ? (float) $value : null;
    }

}
