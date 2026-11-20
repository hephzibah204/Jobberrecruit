<?php

namespace App\Services;

use CodeIgniter\Database\BaseConnection;

/** Manual employer entitlements, serialized and audited independently of payments. */
final class AdminEmployerAccountService
{
    public function __construct(private ?BaseConnection $db = null, private ?\Closure $notify = null)
    {
        $this->db ??= db_connect();
    }

    public static function validate(array $input): array
    {
        $action = $input['action'] ?? null;
        if (!in_array($action, ['subscription', 'bundle', 'credits', 'fund'], true)) {
            throw new \InvalidArgumentException('Choose a valid account action.');
        }
        $reason = $input['reason'] ?? null;
        if (!is_string($reason) || trim($reason) === '' || mb_strlen(trim($reason)) > 200) {
            throw new \InvalidArgumentException('Enter a reason of up to 200 characters.');
        }
        $key = $input['request_key'] ?? null;
        if (!is_string($key) || !preg_match('/\A[a-f0-9]{32}\z/', $key)) {
            throw new \InvalidArgumentException('Refresh the employer page and try again.');
        }
        $data = ['action' => $action, 'reason' => trim($reason), 'request_key' => $key];
        if ($action === 'subscription' || $action === 'bundle') {
            $id = filter_var($input['item_id'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]);
            if ($id === false) throw new \InvalidArgumentException('Select a package.');
            $data['item_id'] = $id;
        }
        if ($action === 'subscription') {
            $date = $input['ends_on'] ?? null;
            $end = is_string($date) ? \DateTimeImmutable::createFromFormat('!Y-m-d', $date) : false;
            if (!$end || $end->format('Y-m-d') !== $date || $date < date('Y-m-d') || $date > date('Y-m-d', strtotime('+10 years'))) {
                throw new \InvalidArgumentException('Choose an expiry date from today through the next 10 years.');
            }
            $data['ends_at'] = $end->format('Y-m-d') . ' 23:59:59';
        }
        if ($action === 'credits') {
            $credits = filter_var($input['credits'] ?? null, FILTER_VALIDATE_INT, ['options' => ['min_range' => 0, 'max_range' => 1000000]]);
            if ($credits === false) throw new \InvalidArgumentException('Job credits must be a whole number from 0 to 1,000,000.');
            $data['credits'] = $credits;
        }
        if ($action === 'fund') {
            $amount = $input['amount'] ?? null;
            if (!is_string($amount) || !preg_match('/\A\d{1,9}(?:\.\d{1,2})?\z/', $amount) || (float) $amount <= 0) {
                throw new \InvalidArgumentException('Enter a positive NGN amount with at most two decimal places.');
            }
            $data['amount'] = number_format((float) $amount, 2, '.', '');
        }
        return $data;
    }

    public function apply(int $employerId, int $adminId, array $input): bool
    {
        $data = self::validate($input);
        if ($adminId <= 0) throw new \InvalidArgumentException('An administrator is required.');
        $employer = $this->db->table('employers')->where('id', $employerId)->get()->getRow();
        if (!$employer || !$employer->user_id) throw new \InvalidArgumentException('Employer account not found.');
        $userId = (int) $employer->user_id;
        $reference = 'admin_account_' . hash('sha256', $employerId . ':' . $adminId . ':' . $data['request_key']);
        $fingerprint = hash('sha256', json_encode($data));

        $event = PurchaseTransaction::run($userId, function () use ($userId, $employerId, $adminId, $data, $reference, $fingerprint) {
            $prior = $this->db->table('job_credit_transactions')->where('reference', $reference)->get()->getRow();
            if ($prior) {
                $meta = json_decode($prior->meta, true) ?? [];
                if (($meta['fingerprint'] ?? '') !== $fingerprint) {
                    throw new \InvalidArgumentException('This form was already submitted. Refresh the page before making another change.');
                }
                return;
            }
            $now = date('Y-m-d H:i:s');
            // Also lock credit rows used by normal job-posting deductions.
            $walletTable = $this->db->protectIdentifiers($this->db->prefixTable('job_credit_wallets'));
            $suffix = $this->db->DBDriver === 'SQLite3' ? '' : ' FOR UPDATE';
            $wallets = $this->db->query("SELECT * FROM {$walletTable} WHERE user_id = ? ORDER BY id{$suffix}", [$userId])->getResult();
            $active = array_values(array_filter($wallets, static fn ($row) => (int) $row->credits > 0 && (!$row->expires_at || $row->expires_at > $now)));
            $before = array_sum(array_map(static fn ($row) => (int) $row->credits, $active));
            $after = $before;
            $meta = ['admin_id' => $adminId, 'employer_id' => $employerId, 'action' => $data['action'], 'reason' => $data['reason'], 'fingerprint' => $fingerprint];

            if ($data['action'] === 'subscription') {
                $plan = $this->db->table('plans')->where('id', $data['item_id'])->where('is_active', 1)->get()->getRow();
                if (!$plan || !in_array($plan->plan_type, ['starter', 'subscription', 'employer'], true)) {
                    throw new \InvalidArgumentException('Select an active employer subscription plan.');
                }
                $this->write($this->db->table('user_subscriptions')->where('user_id', $userId)->where('is_active', 1)->update(['is_active' => 0, 'updated_at' => $now]));
                $this->write($this->db->table('user_subscriptions')->insert([
                    'user_id' => $userId, 'plan_id' => $plan->id, 'starts_at' => $now, 'ends_at' => $data['ends_at'],
                    'is_active' => 1, 'auto_renew' => 0, 'created_at' => $now, 'updated_at' => $now,
                ]));
                foreach ($active as $row) {
                    if ($row->source !== 'subscription') continue;
                    $this->write($this->db->table('job_credit_wallets')->where('id', $row->id)->update(['credits' => 0, 'updated_at' => $now]));
                    $after -= (int) $row->credits;
                }
                $credits = max(0, (int) $plan->monthly_job_credits);
                if ($credits) $this->addCredits($userId, $credits, 'subscription', (int) $plan->id, $data['ends_at'], $now);
                $after += $credits;
                $meta += ['plan_id' => (int) $plan->id, 'package' => $plan->name, 'ends_at' => $data['ends_at']];
            } elseif ($data['action'] === 'bundle') {
                $bundle = $this->db->table('plan_bundles')->where('id', $data['item_id'])->where('is_active', 1)->get()->getRow();
                if (!$bundle || (int) $bundle->job_credits <= 0) throw new \InvalidArgumentException('Select an active job credit bundle.');
                $this->addCredits($userId, (int) $bundle->job_credits, 'bundle', (int) $bundle->id, null, $now);
                $after += (int) $bundle->job_credits;
                $meta['bundle_id'] = (int) $bundle->id;
                $meta['package'] = $bundle->name;
            } elseif ($data['action'] === 'credits') {
                $target = $data['credits'];
                if ($target > $before) {
                    $this->addCredits($userId, $target - $before, 'admin', null, null, $now);
                } else {
                    $remaining = $before - $target;
                    foreach ($active as $row) {
                        if (!$remaining) break;
                        $deduct = min($remaining, (int) $row->credits);
                        $this->write($this->db->table('job_credit_wallets')->where('id', $row->id)->update(['credits' => (int) $row->credits - $deduct, 'updated_at' => $now]));
                        $remaining -= $deduct;
                    }
                }
                $after = $target;
            } else {
                (new WalletService($this->db))->credit($userId, (float) $data['amount'], 'admin', $reference, $adminId, $data['reason']);
                $meta['amount'] = $data['amount'];
            }
            $meta += ['credits_before' => $before, 'credits_after' => $after];
            $this->write($this->db->table('job_credit_transactions')->insert([
                'user_id' => $userId, 'type' => $after === $before ? 'reset' : ($after > $before ? 'credit_in' : 'credit_out'),
                'credits' => abs($after - $before), 'reference' => $reference,
                'description' => 'Admin ' . $data['action'] . ': ' . $data['reason'], 'meta' => json_encode($meta),
                'created_at' => $now, 'updated_at' => $now,
            ]));
            return $meta;
        }, $this->db);
        if (!$event) return true; // An identical retry must not send another notice.
        try {
            if ($this->notify) return (bool) ($this->notify)($employer, $event);
            $user = model(\CodeIgniter\Shield\Models\UserModel::class)->find($userId);
            $title = match ($data['action']) {
                'subscription' => 'Employer subscription assigned', 'bundle' => 'Job credit bundle granted',
                'credits' => 'Job credit balance updated', 'fund' => 'Employer wallet funded',
            };
            $details = ['Company' => $employer->company_name ?? 'Employer', 'Reason' => $data['reason'], 'Reference' => $reference];
            if (isset($event['package'])) $details['Package'] = $event['package'];
            if (isset($event['ends_at'])) $details['Expires'] = $event['ends_at'];
            if ($data['action'] === 'fund') $details['Amount added (NGN)'] = $data['amount'];
            else $details['Available job credits'] = $event['credits_after'];
            return (new EmailNotificationService())->sendEmployerAccountEvent($employer, $user, $title,
                'An administrator has updated your employer account.', $details);
        } catch (\Throwable $e) {
            log_message('error', 'Employer account change email failed: ' . $e->getMessage());
            return false;
        }
    }

    private function addCredits(int $userId, int $credits, string $source, ?int $referenceId, ?string $expiresAt, string $now): void
    {
        $this->write($this->db->table('job_credit_wallets')->insert([
            'user_id' => $userId, 'credits' => $credits, 'source' => $source, 'reference_id' => $referenceId,
            'expires_at' => $expiresAt, 'created_at' => $now, 'updated_at' => $now,
        ]));
    }

    private function write($result): void
    {
        if (!$result) throw new \RuntimeException('The account change could not be saved.');
    }
}
