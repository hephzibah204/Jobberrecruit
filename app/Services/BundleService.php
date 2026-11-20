<?php

namespace App\Services;

use App\Models\PlanBundleModel;
use App\Models\JobCreditWalletModel;
use App\Models\JobCreditTransactionModel;


class BundleService
{
    public function __construct(private ?\CodeIgniter\Database\BaseConnection $db = null)
    {
        $this->db ??= db_connect();
    }

    public function apply(int $userId, string $bundleCode)
    {
        $bundle = (new PlanBundleModel($this->db))->where('slug', $bundleCode)->where('is_active', 1)->first();
        if (!$bundle) throw new \InvalidArgumentException('Bundle is unavailable.');
        $this->credit($userId, (int) $bundle->id, 'bundle_' . bin2hex(random_bytes(16)), 'manual');
    }

    /** Serialize grants and validate that a retry belongs to the same purchase. */
    public function credit(int $userId, int $bundleId, string $reference, string $source = 'paystack'): void
    {
        if (trim($reference) === '') throw new \InvalidArgumentException('A purchase reference is required.');
        PurchaseTransaction::run($userId, function () use ($userId, $bundleId, $reference, $source) {
            $ledger = new JobCreditTransactionModel($this->db);
            $prior = $ledger->where('reference', $reference)->first();
            if ($prior) {
                $meta = json_decode($prior['meta'] ?? '{}', true) ?: [];
                if ((int) $prior['user_id'] !== $userId || (int) ($meta['bundle_id'] ?? 0) !== $bundleId
                    || !in_array($prior['type'], ['credit', 'credit_in'], true)) {
                    throw new \InvalidArgumentException('Purchase reference belongs to a different credit transaction.');
                }
                return;
            }
            $bundle = (new PlanBundleModel($this->db))->find($bundleId);
            if (!$bundle || (int) $bundle->job_credits <= 0) throw new \InvalidArgumentException('Invalid bundle credit allocation.');
            if (!(new JobCreditWalletModel($this->db))->insert([
                'user_id' => $userId, 'credits' => (int) $bundle->job_credits,
                'source' => 'bundle', 'reference_id' => $bundleId, 'expires_at' => null,
            ]) || !$ledger->insert([
                'user_id' => $userId, 'type' => 'credit_in', 'credits' => (int) $bundle->job_credits,
                'reference' => $reference, 'description' => 'Bundle purchase via ' . $source,
                'meta' => json_encode(['bundle_id' => $bundleId, 'source' => $source]),
            ])) throw new \RuntimeException('Bundle credits could not be recorded.');
        }, $this->db);
    }

    public function createPending(int $userId, int $bundleId, float $walletUsed = 0): array
    {
        $bundle = (new PlanBundleModel($this->db))->find($bundleId);
        if (!$bundle || !$bundle->is_active || (int) $bundle->job_credits <= 0) throw new \InvalidArgumentException('Bundle is unavailable.');
        $price = (float) $bundle->price;
        if (!is_finite($price) || $price <= 0 || !is_finite($walletUsed) || $walletUsed < 0 || $walletUsed >= $price) {
            throw new \InvalidArgumentException('Invalid bundle payment amounts.');
        }
        $reference = 'bundle_' . bin2hex(random_bytes(16));
        $amount = round($price - $walletUsed, 2);
        $meta = ['type' => 'bundle', 'user_id' => $userId, 'bundle_id' => $bundleId,
            'wallet_used' => round($walletUsed, 2), 'bundle_price' => $price];
        if (!(new \App\Models\PaymentModel($this->db))->insert([
            'user_id' => $userId, 'reference' => $reference, 'amount' => $amount,
            'currency' => 'NGN', 'status' => 'pending', 'metadata' => json_encode($meta),
        ])) throw new \RuntimeException('Could not prepare bundle payment.');
        return ['reference' => $reference, 'amount' => $amount, 'metadata' => $meta];
    }

    /** Called only with a gateway-verified response or signed webhook payload. */
    public function fulfill(array $data, ?int $expectedUserId = null): array
    {
        $meta = PaymentFulfillmentService::metadata($data);
        $userId = (int) ($meta['user_id'] ?? 0);
        $bundleId = (int) ($meta['bundle_id'] ?? 0);
        if ($userId < 1 || $bundleId < 1 || ($meta['type'] ?? '') !== 'bundle'
            || empty($data['reference']) || ($expectedUserId !== null && $expectedUserId !== $userId)) {
            throw new \InvalidArgumentException('Bundle payment does not belong to this account.');
        }
        $result = PurchaseTransaction::run($userId, function () use ($data, $meta, $userId, $bundleId) {
            $payments = new \App\Models\PaymentModel($this->db);
            $order = $payments->where('reference', $data['reference'])->first();
            $bundle = (new PlanBundleModel($this->db))->find($bundleId);
            $identity = $this->db->table('auth_identities')->where('user_id', $userId)
                ->where('type', 'email_password')->get()->getRow();
            if (!$bundle || (int) $bundle->job_credits <= 0) throw new \InvalidArgumentException('Invalid bundle.');
            $stored = $order ? PaymentFulfillmentService::metadata($order) : $meta;
            $rawWallet = $stored['wallet_used'] ?? 0;
            $walletUsed = is_numeric($rawWallet) ? (float) $rawWallet : -1;
            $price = (float) ($stored['bundle_price'] ?? $bundle->price);
            if (!is_finite($price) || $price <= 0 || !is_finite($walletUsed) || $walletUsed < 0 || $walletUsed >= $price
                || (int) ($stored['bundle_id'] ?? 0) !== $bundleId
                || !is_numeric($meta['wallet_used'] ?? 0)
                || (int) round((float) ($meta['wallet_used'] ?? 0) * 100) !== (int) round($walletUsed * 100)) {
                throw new \InvalidArgumentException('Bundle payment details do not match checkout.');
            }
            if (!$order) {
                // Compatibility with older checkouts: catalog price, never a
                // client-supplied bundle_price, is the authoritative total.
                $price = (float) $bundle->price;
                if (!is_finite($price) || $price <= $walletUsed) throw new \InvalidArgumentException('Invalid bundle total.');
                $stored['bundle_price'] = $price;
                $order = ['user_id' => $userId, 'reference' => $data['reference'], 'amount' => round($price - $walletUsed, 2),
                    'currency' => 'NGN', 'status' => 'pending', 'metadata' => json_encode($stored)];
            }
            PaymentFulfillmentService::validate($data, $order, $meta, (string) ($identity->secret ?? ''));
            $ledger = (new JobCreditTransactionModel($this->db))->where('reference', $data['reference'])->first();
            if ($order['status'] === 'paid' && !$ledger) throw new \RuntimeException('Paid bundle requires credit reconciliation.');
            if ($ledger) {
                $this->credit($userId, $bundleId, $data['reference']); // Validate the prior grant identity.
                if ($order['status'] !== 'paid') {
                    throw new \RuntimeException('Existing bundle credits require payment reconciliation.');
                }
                if ($walletUsed > 0) {
                    $wallet = $this->db->table('wallets')->where('user_id', $userId)->get()->getRow();
                    $debit = $wallet ? $this->db->table('wallet_transactions')->where('wallet_id', $wallet->id)
                        ->where('type', 'debit')->whereIn('reference', ['bundle_wallet_' . $data['reference'],
                            'wallet_part_' . $data['reference'], $data['reference'] . '_wallet'])->get()->getResult() : [];
                    $matchingDebit = array_filter($debit, static fn ($row) => (int) round((float) $row->amount * 100) === (int) round($walletUsed * 100));
                    if (!$matchingDebit) throw new \RuntimeException('Bundle wallet payment requires reconciliation.');
                }
                return ['already_processed' => true];
            }
            if ($walletUsed > 0) {
                (new WalletService($this->db))->debit($userId, $walletUsed, 'bundle_purchase_hybrid',
                    'bundle_wallet_' . $data['reference'], $bundleId, 'Partial bundle payment');
            }
            $this->credit($userId, $bundleId, $data['reference']);
            $record = ['user_id' => $userId, 'reference' => $data['reference'], 'amount' => $order['amount'],
                'amount_paid' => $order['amount'], 'currency' => 'NGN', 'status' => 'paid',
                'paid_at' => date('Y-m-d H:i:s'), 'payment_method' => $data['channel'] ?? 'card',
                'metadata' => json_encode($stored), 'gateway_response' => json_encode($data)];
            $saved = isset($order['id']) ? $payments->update($order['id'], $record) : $payments->insert($record);
            if (!$saved) throw new \RuntimeException('Bundle payment could not be recorded.');
            return ['already_processed' => false, 'payment_id' => $order['id'] ?? $payments->getInsertID(),
                'amount' => $price, 'credits' => (int) $bundle->job_credits];
        }, $this->db);
        if (!$result['already_processed'] && ENVIRONMENT !== 'testing') {
            try {
                (new InvoiceService())->sendBundleInvoice($userId, $bundleId, $result['payment_id'], $result['amount'], $result['credits']);
            } catch (\Throwable $e) {
                log_message('error', 'Bundle invoice failed after fulfillment: ' . $e->getMessage());
            }
        }
        return $result;
    }

    /**
     * Feature eligibility granted by bundles
     */
    public function allows(string $feature): bool
    {
        return in_array($feature, [
            'anonymous_job',
            'network_blast',
            'featured_job',
        ], true);
    }
}
