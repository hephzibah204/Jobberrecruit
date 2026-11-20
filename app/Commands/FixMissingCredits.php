<?php

namespace App\Commands;

use App\Services\PurchaseTransaction;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixMissingCredits extends BaseCommand
{
    protected $group = 'Billing';
    protected $name = 'fix:missing_credits';
    protected $description = 'Restore missing finite-plan allocations without refilling spent credits or changing unlimited plans';

    public function run(array $params)
    {
        $db = db_connect();
        $now = date('Y-m-d H:i:s');
        $subs = $db->table('user_subscriptions')->where('is_active', 1)
            ->where('starts_at <=', $now)->where('ends_at >', $now)->get()->getResultArray();
        foreach ($subs as $sub) {
            PurchaseTransaction::run((int) $sub['user_id'], function () use ($db, $sub, $now) {
                // Canonical IDs always reference plans, never subscription_plans.
                $plan = $db->table('plans')->where('id', $sub['plan_id'])->get()->getRow();
                if (!$plan || $plan->plan_type === 'candidate'
                    || (new \App\Services\CreditService())->planProvidesUnlimitedPosting($plan)
                    || (int) $plan->monthly_job_credits <= 0) return;
                $reference = 'retroactive_fix_' . $sub['id'];
                if ($db->table('job_credit_transactions')->where('user_id', $sub['user_id'])
                    ->where('reference', $reference)->countAllResults()) return;
                // Include spent wallets. A zero remaining balance must never
                // cause a second allocation. Legacy non-expiring allocations
                // are ambiguous and require manual reconciliation.
                $allocated = $db->table('job_credit_wallets')->where('user_id', $sub['user_id'])
                    ->where('source', 'subscription')->groupStart()->where('expires_at', $sub['ends_at'])
                    ->orWhere('expires_at', null)->groupEnd()->countAllResults();
                if ($allocated) return;
                $months = max(1, (int) round((strtotime($sub['ends_at']) - strtotime($sub['starts_at'])) / (30 * 86400)));
                $amount = (int) $plan->monthly_job_credits * $months;
                if (!$db->table('job_credit_wallets')->insert(['user_id' => $sub['user_id'], 'credits' => $amount,
                    'source' => 'subscription', 'reference_id' => $sub['id'], 'expires_at' => $sub['ends_at'],
                    'created_at' => $now, 'updated_at' => $now])
                    || !$db->table('job_credit_transactions')->insert(['user_id' => $sub['user_id'],
                        'type' => 'credit_in', 'credits' => $amount, 'reference' => $reference,
                        'description' => 'Restored missing subscription allocation',
                        'meta' => json_encode(['subscription_id' => $sub['id']]), 'created_at' => $now, 'updated_at' => $now])) {
                    throw new \RuntimeException('Credit repair failed; transaction rolled back.');
                }
                CLI::write('Restored ' . $amount . ' credits for subscription ' . $sub['id']);
            }, $db);
        }
        CLI::write('Reconciliation complete. Unlimited plans use their active posting entitlement.');
    }
}
