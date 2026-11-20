<?php

namespace App\Commands;

use App\Services\CreditService;
use App\Services\PaymentFulfillmentService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/** Read-only checks for deployment and historical payment reconciliation. */
class AuditSubscriptions extends BaseCommand
{
    protected $group = 'Billing';
    protected $name = 'subscriptions:audit';
    protected $description = 'Check all subscription entitlements and paid subscription linkage without changing balances';

    public function run(array $params)
    {
        $db = db_connect();
        $credits = new CreditService();
        $now = date('Y-m-d H:i:s');
        $issues = 0;
        $active = 0;
        $unlimited = 0;
        $plans = [];
        foreach ($db->table('plans')->get()->getResult() as $plan) $plans[(int) $plan->id] = $plan;
        $subscriptions = $db->table('user_subscriptions')->get()->getResultArray();
        $byId = array_column($subscriptions, null, 'id');
        foreach ($subscriptions as $sub) {
            $plan = $plans[(int) $sub['plan_id']] ?? null;
            if (!$plan) {
                CLI::write('Subscription ' . $sub['id'] . ': missing canonical plan ' . $sub['plan_id'], 'red');
                $issues++;
                continue;
            }
            if (!(int) $sub['is_active'] || $sub['starts_at'] > $now || $sub['ends_at'] <= $now) continue;
            $active++;
            if ($credits->planProvidesUnlimitedPosting($plan)) {
                $unlimited++;
            } elseif ($plan->plan_type !== 'candidate' && (int) $plan->monthly_job_credits <= 0) {
                CLI::write('Subscription ' . $sub['id'] . ': employer plan has no posting entitlement or credits', 'red');
                $issues++;
            } elseif ($plan->plan_type !== 'candidate') {
                // A spent wallet still proves allocation; never treat its
                // zero balance as permission to refill a subscription.
                $allocated = $db->table('job_credit_wallets')->where('user_id', $sub['user_id'])
                    ->where('source', 'subscription')->where('expires_at', $sub['ends_at'])->countAllResults();
                if (!$allocated) {
                    CLI::write('Subscription ' . $sub['id'] . ': finite plan has no matching credit allocation', 'red');
                    $issues++;
                }
            }
        }
        $paid = 0;
        foreach ($db->table('payments')->where('status', 'paid')->get()->getResultArray() as $payment) {
            $meta = PaymentFulfillmentService::metadata($payment);
            if (!in_array($meta['type'] ?? '', ['subscription', 'employer_plan', 'candidate_plan'], true)) continue;
            $paid++;
            $id = $meta['subscription_id'] ?? $meta['fulfillment']['subscription_id'] ?? null;
            $matches = $id ? (isset($byId[$id]) ? [$byId[$id]] : []) : array_filter($subscriptions,
                static fn ($sub) => (int) $sub['user_id'] === (int) $payment['user_id']
                    && (int) $sub['plan_id'] === (int) $payment['plan_id']
                    && abs(strtotime($sub['starts_at']) - strtotime($payment['paid_at'] ?? $payment['created_at'])) <= 300);
            $matches = array_filter($matches, static fn ($sub) => (int) $sub['user_id'] === (int) $payment['user_id']
                && (int) $sub['plan_id'] === (int) $payment['plan_id']);
            if (!$matches) {
                CLI::write('Payment ' . $payment['id'] . ': paid subscription has no matching activation', 'red');
                $issues++;
            }
        }
        CLI::write('Subscriptions checked: ' . count($subscriptions) . '; active: ' . $active
            . '; unlimited: ' . $unlimited . '; subscription payments checked: ' . $paid . '; issues: ' . $issues);
        CLI::write('Read-only audit completed. Unlimited subscriptions do not require numeric credit deposits.');
    }
}
