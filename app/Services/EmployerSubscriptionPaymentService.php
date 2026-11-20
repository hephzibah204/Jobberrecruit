<?php

namespace App\Services;

use App\Models\PlanModel;
use App\Models\PaymentModel;
use App\Models\UserSubscriptionModel;
use CodeIgniter\Database\BaseConnection;

/** One atomic activation for both the signed webhook and browser return. */
final class EmployerSubscriptionPaymentService
{
    public function __construct(private ?BaseConnection $db = null)
    {
        $this->db ??= db_connect();
    }

    public function fulfill(array $data, ?int $expectedUserId = null): array
    {
        $meta = PaymentFulfillmentService::metadata($data);
        $userId = (int) ($meta['user_id'] ?? 0);
        if ($userId < 1 || ($expectedUserId !== null && $expectedUserId !== $userId)
            || ($meta['type'] ?? '') !== 'subscription' || empty($data['reference'])) {
            throw new \InvalidArgumentException('Subscription payment does not belong to this account.');
        }
        $result = PurchaseTransaction::run($userId, function () use ($data, $meta, $userId) {
            $payments = new PaymentModel($this->db);
            $plans = new PlanModel($this->db);
            $subscriptions = new UserSubscriptionModel($this->db);
            $order = $payments->where('reference', $data['reference'])->first();
            $identity = $this->db->table('auth_identities')->where('user_id', $userId)
                ->where('type', 'email_password')->get()->getRow();
            $employer = $this->db->table('employers')->where('user_id', $userId)->get()->getRow();
            $plan = $plans->find((int) ($meta['plan_id'] ?? 0));
            if (!$plan || $plan->plan_type === 'candidate' || !$employer) {
                throw new \InvalidArgumentException('Employer subscription plan not found.');
            }
            $months = filter_var($meta['months'] ?? 1, FILTER_VALIDATE_INT);
            if ($months === false || $months < 1 || $months > 36) {
                throw new \InvalidArgumentException('Invalid subscription duration.');
            }
            // Older checkouts did not save an order. Only accept their full
            // catalog price; discounted checkouts must have a saved order.
            if (!$order) {
                $tiers = is_string($plan->pricing_tiers) ? json_decode($plan->pricing_tiers, true) : $plan->pricing_tiers;
                $price = SubscriptionService::priceForDuration($months, (float) $plan->base_price, is_array($tiers) ? $tiers : []);
                $order = ['user_id' => $userId, 'plan_id' => $plan->id, 'reference' => $data['reference'],
                    'amount' => $price, 'currency' => 'NGN', 'status' => 'pending', 'metadata' => json_encode($meta)];
            }
            PaymentFulfillmentService::validate($data, $order, $meta, (string) ($identity->secret ?? ''));
            $stored = PaymentFulfillmentService::metadata($order);
            if ((int) ($stored['months'] ?? 1) !== $months) {
                throw new \InvalidArgumentException('Payment duration does not match the order.');
            }
            if ($order['status'] === 'paid') {
                $query = $subscriptions->where('user_id', $userId)->where('plan_id', $plan->id);
                if (!empty($stored['subscription_id'])) {
                    $query->where('id', $stored['subscription_id']);
                } else {
                    $paidAt = strtotime($order['paid_at'] ?? $order['created_at'] ?? '');
                    if (!$paidAt) throw new \RuntimeException('Paid subscription requires reconciliation.');
                    $query->where('starts_at >=', date('Y-m-d H:i:s', $paidAt - 300))
                        ->where('starts_at <=', date('Y-m-d H:i:s', $paidAt + 300));
                }
                $existing = $query->first();
                if (!$existing) throw new \RuntimeException('Paid subscription has no activation. Reconciliation is required.');
                return ['subscription_id' => (int) $existing['id'], 'already_processed' => true];
            }
            $now = date('Y-m-d H:i:s');
            $end = date('Y-m-d H:i:s', strtotime("+{$months} months"));
            if (!$subscriptions->where('user_id', $userId)->where('is_active', 1)->set(['is_active' => 0])->update()) {
                throw new \RuntimeException('Could not replace the previous subscription.');
            }
            $id = $subscriptions->insert(['user_id' => $userId, 'plan_id' => $plan->id,
                'starts_at' => $now, 'ends_at' => $end, 'is_active' => 1,
                'auto_renew' => !empty($data['subscription']['subscription_code']) ? 1 : 0,
                'subscription_code' => $data['subscription']['subscription_code'] ?? null,
                'email_token' => $data['subscription']['email_token'] ?? null]);
            if (!$id) throw new \RuntimeException('Subscription activation failed.');
            // Unlimited access is read from the active plan, independently of
            // the optional employer override and the numeric credit wallet.
            if ((int) $plan->monthly_job_credits > 0) {
                $credits = (int) $plan->monthly_job_credits * $months;
                $wallets = new \App\Models\JobCreditWalletModel($this->db);
                $ledger = new \App\Models\JobCreditTransactionModel($this->db);
                if (!$wallets->insert(['user_id' => $userId, 'credits' => $credits, 'source' => 'subscription',
                    'reference_id' => $id, 'expires_at' => $end])
                    || !$ledger->insert(['user_id' => $userId, 'type' => 'credit_in', 'credits' => $credits,
                        'reference' => $data['reference'], 'description' => 'Subscription credits',
                        'meta' => json_encode(['subscription_id' => $id])])) {
                    throw new \RuntimeException('Subscription credits could not be saved.');
                }
            }
            $stored['subscription_id'] = $id;
            $stored['subscription_value'] = $stored['proration']['new_plan_price'] ?? $order['amount'];
            $record = ['user_id' => $userId, 'employer_id' => $employer->id, 'plan_id' => $plan->id,
                'reference' => $data['reference'], 'amount' => $order['amount'], 'amount_paid' => $order['amount'],
                'currency' => 'NGN', 'status' => 'paid', 'paid_at' => $now,
                'payment_method' => $data['channel'] ?? 'card', 'metadata' => json_encode($stored),
                'gateway_response' => json_encode($data)];
            $saved = isset($order['id']) ? $payments->update($order['id'], $record) : $payments->insert($record);
            if (!$saved) throw new \RuntimeException('Subscription payment could not be recorded.');
            return ['subscription_id' => $id, 'payment_id' => $order['id'] ?? $payments->getInsertID(),
                'amount' => (float) $order['amount'], 'months' => $months,
                'proration' => $stored['proration'] ?? null, 'already_processed' => false];
        }, $this->db);
        if (!$result['already_processed'] && ENVIRONMENT !== 'testing') {
            try {
                (new InvoiceService())->sendSubscriptionInvoice($userId, $result['subscription_id'],
                    $result['payment_id'], $result['amount'], $result['months'], $result['proration']);
            } catch (\Throwable $e) {
                log_message('error', 'Subscription invoice failed after activation: ' . $e->getMessage());
            }
        }
        return $result;
    }
}
