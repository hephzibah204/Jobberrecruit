<?php

namespace App\Services;

use App\Models\JobCreditWalletModel;
use App\Models\JobCreditTransactionModel;
use App\Models\UserSubscriptionModel;
use App\Models\PlanModel;
use App\Models\EmployerModel;

class CreditService
{
    protected $walletModel;
    protected $transactionModel;
    protected $subscriptionModel;
    protected $planModel;
    protected $employerModel;

    public function __construct()
    {
        $this->walletModel       = model(JobCreditWalletModel::class);
        $this->transactionModel  = model(JobCreditTransactionModel::class);
        $this->subscriptionModel = model(UserSubscriptionModel::class);
        $this->planModel         = model(PlanModel::class);
        $this->employerModel     = model(EmployerModel::class);
    }

    /**
     * Get total available credits for a user
     */
    public function getAvailableCredits(int $userId): int
    {
        $result = $this->walletModel
            ->select('SUM(credits) as total')
            ->where('user_id', $userId)
            ->where('credits >', 0)
            ->where('(expires_at IS NULL OR expires_at > NOW())')
            ->get()
            ->getRow();

        return $result ? (int)$result->total : 0;
    }

    /**
     * Check if user has unlimited access
     */
    public function hasUnlimitedAccess(int $userId): bool
    {
        $employer = $this->employerModel->where('user_id', $userId)->first();

        if (!$employer) {
            return false;
        }

        // Check if employer has unlimited access and it hasn't expired
        if ($employer->unlimited_access == 1) {
            if (empty($employer->unlimited_until) || strtotime($employer->unlimited_until) > time()) {
                return true;
            }
        }

        // Check if parent employer has unlimited access
        if (!empty($employer->parent_employer_id)) {
            $parentEmployer = $this->employerModel->find($employer->parent_employer_id);
            if ($parentEmployer && $parentEmployer->unlimited_access == 1) {
                if (empty($parentEmployer->unlimited_until) || strtotime($parentEmployer->unlimited_until) > time()) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Get current subscription plan for user
     */
    public function getCurrentPlan(int $userId): ?object
    {
        $subscription = $this->subscriptionModel
            ->where('user_id', $userId)
            ->where('is_active', 1)
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->first();

        if (!$subscription) {
            return null;
        }

        return $this->planModel->find($subscription->plan_id);
    }

    /**
     * Get current active subscription
     */
    public function getCurrentSubscription(int $userId): ?object
    {
        return $this->subscriptionModel
            ->where('user_id', $userId)
            ->where('is_active', 1)
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->first();
    }

    /**
     * Add credits when buying bundle or starting subscription
     */
    public function addCredits(int $userId, int $credits, string $source, $referenceId, ?string $expiresAt = null): bool
    {
        $this->walletModel->insert([
            'user_id'       => $userId,
            'credits'       => $credits,
            'source'        => $source,           // 'bundle' or 'subscription'
            'reference_id'  => $referenceId,
            'expires_at'    => $expiresAt
        ]);

        $this->transactionModel->insert([
            'user_id'     => $userId,
            'type'        => 'credit_in',
            'credits'     => $credits,
            'reference'   => (string)$referenceId,
            'description' => ucfirst($source) . " credits added: {$credits} credits",
            'meta'        => json_encode(['source' => $source, 'credits' => $credits])
        ]);

        return true;
    }

    /**
     * Check if user can perform an action (STRICT - must have credits OR subscription OR unlimited)
     */
    public function canPerformAction(int $userId, string $action = 'post_job'): array
    {
        // FIRST: Check for unlimited access (highest priority)
        if ($this->hasUnlimitedAccess($userId)) {
            return [
                'can'       => true,
                'reason'    => '',
                'unlimited' => true,
                'credits'   => PHP_INT_MAX,
                'source'    => 'unlimited'
            ];
        }

        // SECOND: Check for active subscription with credits
        $subscription = $this->getCurrentSubscription($userId);
        $plan = null;
        $planFeatures = [];

        if ($subscription) {
            $plan = $this->planModel->find($subscription->plan_id);
            if ($plan) {
                $planFeatures = is_string($plan->features)
                    ? json_decode($plan->features, true)
                    : ($plan->features ?? []);
            }
        }

        // THIRD: Check for bundle credits
        $creditBalance = $this->getAvailableCredits($userId);

        // Special handling for post_job action
        if ($action === 'post_job') {
            // Case 1: Has active subscription with monthly credits available
            if ($subscription && $creditBalance >= 1) {
                return [
                    'can'       => true,
                    'reason'    => '',
                    'unlimited' => false,
                    'credits'   => $creditBalance,
                    'source'    => 'subscription',
                    'plan_name' => $plan ? $plan->name : 'Subscription',
                    'plan_features' => $planFeatures
                ];
            }

            // Case 2: Has bundle credits (no active subscription)
            if (!$subscription && $creditBalance >= 1) {
                return [
                    'can'       => true,
                    'reason'    => '',
                    'unlimited' => false,
                    'credits'   => $creditBalance,
                    'source'    => 'bundle',
                    'plan_name' => 'Pay As You Go',
                    'plan_features' => []
                ];
            }

            // Case 3: Has active subscription but NO credits left
            if ($subscription && $creditBalance < 1) {
                return [
                    'can'       => false,
                    'reason'    => "You have an active {$plan->name} subscription but no monthly credits remaining. Your next allocation will be on " . date('M d, Y', strtotime($subscription->ends_at)) . ". Purchase a bundle for immediate credits.",
                    'unlimited' => false,
                    'credits'   => $creditBalance,
                    'source'    => 'subscription_no_credits'
                ];
            }

            // Case 4: No subscription and no credits
            return [
                'can'       => false,
                'reason'    => 'You need either an active subscription or job credits to post a job. Subscribe now or purchase a bundle.',
                'unlimited' => false,
                'credits'   => 0,
                'source'    => 'none'
            ];
        }

        // For other actions that don't consume credits, just check feature availability
        $featureAvailable = $planFeatures[$action] ?? false;

        if ($featureAvailable) {
            return [
                'can'       => true,
                'reason'    => '',
                'unlimited' => false,
                'credits'   => $creditBalance,
                'source'    => 'subscription_feature'
            ];
        }

        return [
            'can'       => false,
            'reason'    => ucwords(str_replace('_', ' ', $action)) . ' is not available in your current plan. Please upgrade.',
            'unlimited' => false,
            'credits'   => $creditBalance
        ];
    }

    /**
     * Deduct credits - STRICT checking
     */
    public function deductCredits(int $userId, int $amount, string $reference, string $description, string $action = 'post_job'): array
    {
        // Check if user has unlimited access - no deduction needed
        if ($this->hasUnlimitedAccess($userId)) {
            return [
                'success'   => true,
                'remaining' => PHP_INT_MAX,
                'message'   => 'Unlimited access - no credits deducted',
                'unlimited' => true
            ];
        }

        // Strict check before deduction
        $check = $this->canPerformAction($userId, $action);
        if (!$check['can']) {
            return ['success' => false, 'message' => $check['reason']];
        }

        // If from subscription, ensure credits are available
        if ($check['source'] === 'subscription' && $check['credits'] < $amount) {
            return [
                'success' => false,
                'message' => 'Insufficient subscription credits. Please wait for your next monthly allocation or purchase a bundle.'
            ];
        }

        $db = db_connect();
        $db->transStart();

        try {
            // Get wallets (FIFO - oldest first, expiring soonest priority)
            $wallets = $this->walletModel
                ->where('user_id', $userId)
                ->where('credits >', 0)
                ->where('(expires_at IS NULL OR expires_at > NOW())')
                ->orderBy('expires_at', 'ASC')
                ->orderBy('created_at', 'ASC')
                ->get()
                ->getResult();

            if (empty($wallets)) {
                throw new \Exception('No credits available to deduct');
            }

            $remaining = $amount;
            $deductedFrom = [];

            foreach ($wallets as $wallet) {
                if ($remaining <= 0) break;

                $deductNow = min($remaining, $wallet->credits);
                $newBalance = $wallet->credits - $deductNow;

                $this->walletModel->update($wallet->id, [
                    'credits' => $newBalance
                ]);

                $deductedFrom[] = [
                    'source' => $wallet->source,
                    'amount' => $deductNow,
                    'remaining_balance' => $newBalance,
                    'wallet_id' => $wallet->id
                ];

                $remaining -= $deductNow;
            }

            if ($remaining > 0) {
                throw new \Exception('Insufficient credits to complete deduction. Needed: ' . $amount);
            }

            // Record transaction
            $this->transactionModel->insert([
                'user_id'     => $userId,
                'type'        => 'credit_out',
                'credits'     => $amount,
                'reference'   => $reference,
                'description' => $description,
                'meta'        => json_encode([
                    'action' => $action,
                    'deducted_from' => $deductedFrom,
                    'timestamp' => date('Y-m-d H:i:s')
                ])
            ]);

            $db->transComplete();

            $remainingCredits = $this->getAvailableCredits($userId);

            return [
                'success'   => true,
                'remaining' => $remainingCredits,
                'message'   => "Successfully deducted {$amount} credit(s). Remaining: {$remainingCredits}",
                'unlimited' => false,
                'deducted_from' => $deductedFrom
            ];
        } catch (\Exception $e) {
            $db->transRollback();
            log_message('error', 'Credit deduction failed for user ' . $userId . ': ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Failed to deduct credits: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Get credit summary for user
     */
    public function getCreditSummary(int $userId): array
    {
        $hasUnlimited = $this->hasUnlimitedAccess($userId);

        if ($hasUnlimited) {
            return [
                'total_credits' => PHP_INT_MAX,
                'has_unlimited' => true,
                'sources' => [],
                'subscription_active' => true,
                'can_post' => true
            ];
        }

        $wallets = $this->walletModel
            ->where('user_id', $userId)
            ->where('credits >', 0)
            ->where('(expires_at IS NULL OR expires_at > NOW())')
            ->orderBy('expires_at', 'ASC')
            ->get()
            ->getResult();

        $total = 0;
        $sources = [];

        foreach ($wallets as $wallet) {
            $total += $wallet->credits;
            $sources[] = [
                'source' => $wallet->source,
                'credits' => $wallet->credits,
                'expires_at' => $wallet->expires_at
            ];
        }

        $subscription = $this->getCurrentSubscription($userId);
        $canPost = ($total >= 1) || !empty($subscription);

        return [
            'total_credits' => $total,
            'has_unlimited' => false,
            'sources' => $sources,
            'subscription_active' => !empty($subscription),
            'subscription_ends_at' => $subscription ? $subscription->ends_at : null,
            'can_post' => $canPost,
            'credits_needed' => $canPost ? 0 : 1
        ];
    }

    /**
     * Add monthly subscription credits
     */
    public function addMonthlySubscriptionCredits(int $userId, int $subscriptionId): bool
    {
        $subscription = $this->subscriptionModel->find($subscriptionId);
        if (!$subscription) {
            return false;
        }

        $plan = $this->planModel->find($subscription->plan_id);
        if (!$plan || $plan->monthly_job_credits <= 0) {
            return false;
        }

        return $this->addCredits(
            $userId,
            $plan->monthly_job_credits,
            'subscription_monthly',
            $subscriptionId,
            $subscription->ends_at
        );
    }
}
