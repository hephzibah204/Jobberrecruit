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

    protected ?\CodeIgniter\Database\BaseConnection $db = null;

    public function __construct(?\CodeIgniter\Database\BaseConnection $db = null)
    {
        $this->db = $db ?? db_connect();
        $this->walletModel       = new JobCreditWalletModel($this->db);
        $this->transactionModel  = new JobCreditTransactionModel($this->db);
        $this->subscriptionModel = new UserSubscriptionModel($this->db);
        $this->planModel         = new PlanModel($this->db);
        $this->employerModel     = new EmployerModel($this->db);
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
            ->groupStart()
            ->where('expires_at', null)
            ->orWhere('expires_at >', date('Y-m-d H:i:s'))
            ->groupEnd()
            ->get()
            ->getRow();

        return $result ? (int)$result->total : 0;
    }

    /**
     * Check if user has unlimited access
     */
    public function hasUnlimitedAccess(int $userId): bool
    {
        if (is_site_free_mode()) {
            return true;
        }

        $employer = $this->employerModel->where('user_id', $userId)->first();

        if (!$employer) {
            return false;
        }

        // Check if employer has unlimited access and it hasn't expired
        if ($employer->unlimited_access == 1) {
            if (empty($employer->unlimited_until) || strpos($employer->unlimited_until, '0000') !== false || strtotime($employer->unlimited_until) > time()) {
                return true;
            }
        }

        // A paid unlimited subscription is an entitlement in its own right.
        // Do not require the separate admin-only employer flag to be synchronized.
        $activePlan = $this->getCurrentPlan($userId);
        if ($activePlan && $this->planProvidesUnlimitedPosting($activePlan)) {
            return true;
        }

        // Check if parent employer has unlimited access
        if (!empty($employer->parent_employer_id)) {
            $parentEmployer = $this->employerModel->find($employer->parent_employer_id);
            if ($parentEmployer && $parentEmployer->unlimited_access == 1) {
                if (empty($parentEmployer->unlimited_until) || strtotime($parentEmployer->unlimited_until) > time()) {
                    return true;
                }
            }

            if ($parentEmployer && !empty($parentEmployer->user_id)) {
                $parentPlan = $this->getCurrentPlan((int) $parentEmployer->user_id);
                if ($parentPlan && $this->planProvidesUnlimitedPosting($parentPlan)) {
                    return true;
                }
            }
        }

        return false;
    }

    /**
     * Determine whether a plan grants unlimited employer job postings.
     * Supports the explicit feature key going forward and legacy plan naming.
     */
    public function planProvidesUnlimitedPosting($plan): bool
    {
        $value = static function (string $field) use ($plan) {
            return is_array($plan) ? ($plan[$field] ?? null) : ($plan->{$field} ?? null);
        };

        $planType = strtolower(trim((string) $value('plan_type')));
        if ($planType === 'candidate') {
            return false;
        }

        // Business Pro was sold as unlimited even on installations whose
        // feature JSON predates the unlimited_jobs flag.
        if (strtolower(trim((string) $value('code'))) === 'business_pro'
            || strtolower(trim((string) $value('name'))) === 'business pro') {
            return true;
        }

        $features = $value('features') ?? [];
        if (is_string($features)) {
            $features = json_decode($features, true) ?: [];
        } elseif (is_object($features)) {
            $features = (array) $features;
        }
        if (!is_array($features)) $features = [];

        foreach (['unlimited_job_postings', 'unlimited_posting', 'unlimited_jobs', 'unlimited'] as $feature) {
            if (!empty($features[$feature])) {
                return true;
            }
        }

        // Backward compatibility for plans already sold as "Unlimited" before
        // the explicit feature was introduced.
        $identity = strtolower(trim((string) $value('code') . ' ' . (string) $value('name')));
        return str_contains($identity, 'unlimited');
    }

    public function getCurrentPlan(int $userId): ?object
    {
        $subscription = $this->subscriptionModel
            ->where('user_id', $userId)
            ->where('is_active', 1)
            ->where('starts_at <=', date('Y-m-d H:i:s'))
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->orderBy('ends_at', 'DESC')
            ->first();

        if (!$subscription) {
            return null;
        }

        $planId = is_array($subscription) ? ($subscription['plan_id'] ?? null) : ($subscription->plan_id ?? null);
        if (!$planId) {
            return null;
        }

        $plan = $this->planModel->find($planId);
        return $plan ?: null;
    }

    /**
     * Get current active subscription
     */
    public function getCurrentSubscription(int $userId): ?object
    {
        $subscription = $this->subscriptionModel
            ->where('user_id', $userId)
            ->where('is_active', 1)
            ->where('starts_at <=', date('Y-m-d H:i:s'))
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->orderBy('ends_at', 'DESC')
            ->first();

        return $subscription ? (object)$subscription : null;
    }

    /**
     * Add credits when buying bundle or starting subscription
     */
    public function addCredits(int $userId, int $credits, string $source, $referenceId, ?string $expiresAt = null, ?string $transactionReference = null): bool
    {
        if ($credits <= 0) throw new \InvalidArgumentException('Credit amount must be positive');
        return PurchaseTransaction::run($userId, function () use ($userId, $credits, $source, $referenceId, $expiresAt, $transactionReference) {
            // Wallet linkage is an integer ID; gateway references belong in the ledger.
            $walletReference = filter_var($referenceId, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if (!$this->walletModel->insert([
                'user_id' => $userId, 'credits' => $credits, 'source' => $source,
                'reference_id' => $walletReference === false ? null : $walletReference,
                'expires_at' => $expiresAt,
            ]) || !$this->transactionModel->insert([
                'user_id' => $userId, 'type' => 'credit_in', 'credits' => $credits,
                'reference' => $transactionReference ?? (string) $referenceId,
                'description' => ucfirst($source) . " credits added: {$credits} credits",
                'meta' => json_encode(['source' => $source, 'credits' => $credits]),
            ])) throw new \RuntimeException('Credit allocation could not be recorded.');
            return true;
        }, $this->db ?? db_connect());
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

            // Case 3: Has active subscription but NO credits left (fallback to wallet)
            if ($subscription && $creditBalance < 1) {
                return [
                    'can'       => true,
                    'reason'    => '',
                    'unlimited' => false,
                    'credits'   => $creditBalance,
                    'source'    => 'wallet'
                ];
            }

            // Case 4: No subscription and no credits (fallback to wallet)
            return [
                'can'       => true,
                'reason'    => '',
                'unlimited' => false,
                'credits'   => 0,
                'source'    => 'wallet'
            ];
        }

        // For other actions that don't consume credits, just check feature availability
        $featureAvailable = $planFeatures[$action] ?? false;

        // Custom bypass: If the action is unlock_candidate, they are paying ₦5,000 via their wallet.
        // We should allow this action regardless of subscription plan features, as long as they pay.
        if ($action === 'unlock_candidate') {
            $featureAvailable = true;
        }

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
        if ($amount <= 0 || trim($reference) === '') {
            return ['success' => false, 'message' => 'A positive credit amount and reference are required.'];
        }
        if ($this->hasUnlimitedAccess($userId)) {
            return ['success' => true, 'remaining' => PHP_INT_MAX, 'message' => 'Unlimited access - no credits deducted', 'unlimited' => true];
        }
        $check = $this->canPerformAction($userId, $action);
        if (!$check['can']) return ['success' => false, 'message' => $check['reason']];
        $db = $this->db ?? db_connect();
        try {
            return PurchaseTransaction::run($userId, function () use ($db, $userId, $amount, $reference, $description, $action) {
                $prior = $this->transactionModel->where('user_id', $userId)->where('type', 'credit_out')
                    ->where('reference', $reference)->first();
                if ($prior) {
                    $meta = json_decode($prior['meta'] ?? '{}', true) ?: [];
                    if ((int) $prior['credits'] !== $amount || ($meta['action'] ?? '') !== $action) {
                        throw new \RuntimeException('Credit reference was already used for a different deduction.');
                    }
                    return ['success' => true, 'remaining' => $this->getAvailableCredits($userId),
                        'message' => 'Credits already deducted for this action.', 'unlimited' => false,
                        'deducted_from' => $meta['deducted_from'] ?? [], 'already_processed' => true];
                }
                $table = $db->protectIdentifiers($db->prefixTable('job_credit_wallets'));
                $lock = $db->DBDriver === 'SQLite3' ? '' : ' FOR UPDATE';
                $wallets = $db->query("SELECT * FROM {$table} WHERE user_id = ? AND credits > 0
                    AND (expires_at IS NULL OR expires_at > ?)
                    ORDER BY CASE WHEN expires_at IS NULL THEN 1 ELSE 0 END, expires_at ASC, created_at ASC, id ASC{$lock}",
                    [$userId, date('Y-m-d H:i:s')])->getResult();
                $remaining = $amount;
                $deductedFrom = [];
                foreach ($wallets as $wallet) {
                    if ($remaining <= 0) break;
                    $deduct = min($remaining, (int) $wallet->credits);
                    $balance = (int) $wallet->credits - $deduct;
                    if (!$this->walletModel->update($wallet->id, ['credits' => $balance])) {
                        throw new \RuntimeException('Credit balance could not be updated.');
                    }
                    $deductedFrom[] = ['source' => $wallet->source, 'amount' => $deduct,
                        'remaining_balance' => $balance, 'wallet_id' => $wallet->id];
                    $remaining -= $deduct;
                }
                if ($remaining > 0) throw new \RuntimeException('Insufficient credits to complete deduction.');
                if (!$this->transactionModel->insert([
                    'user_id' => $userId, 'type' => 'credit_out', 'credits' => $amount,
                    'reference' => $reference, 'description' => $description,
                    'meta' => json_encode(['action' => $action, 'deducted_from' => $deductedFrom,
                        'timestamp' => date('Y-m-d H:i:s')]),
                ])) throw new \RuntimeException('Credit deduction could not be recorded.');
                $balance = $this->getAvailableCredits($userId);
                return ['success' => true, 'remaining' => $balance, 'message' => "Successfully deducted {$amount} credit(s). Remaining: {$balance}",
                    'unlimited' => false, 'deducted_from' => $deductedFrom];
            }, $db);
        } catch (\Throwable $e) {
            log_message('error', 'Credit deduction failed for user ' . $userId . ': ' . $e->getMessage());
            return ['success' => false, 'message' => 'Failed to deduct credits: ' . $e->getMessage()];
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
            ->groupStart()
            ->where('expires_at', null)
            ->orWhere('expires_at >', date('Y-m-d H:i:s'))
            ->groupEnd()
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
        if (!$subscription || (int) $subscription['user_id'] !== $userId
            || !(int) $subscription['is_active'] || $subscription['ends_at'] <= date('Y-m-d H:i:s')) {
            return false;
        }

        $plan = $this->planModel->find($subscription['plan_id']);
        if (!$plan || $plan->monthly_job_credits <= 0) {
            return false;
        }

        return $this->addCredits(
            $userId,
            $plan->monthly_job_credits,
            'subscription_monthly',
            $subscriptionId,
            $subscription['ends_at']
        );
    }
}
