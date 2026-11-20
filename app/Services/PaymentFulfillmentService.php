<?php

namespace App\Services;

use App\Models\PaymentModel;
use App\Models\CvReviewModel;

/** Fulfillment shared by browser returns and signed gateway webhooks. */
class PaymentFulfillmentService
{
    private \CodeIgniter\Database\BaseConnection $db;
    private PaymentModel $payments;
    private CvReviewModel $reviews;

    public function __construct(?\CodeIgniter\Database\BaseConnection $db = null)
    {
        $this->db = $db ?? db_connect();
        $this->payments = new PaymentModel($this->db);
        $this->reviews = new CvReviewModel($this->db);
    }

    public static function metadata(array $data): array
    {
        $meta = $data['metadata'] ?? [];
        if (is_string($meta)) {
            $meta = json_decode($meta, true);
        }
        $meta = is_array($meta) ? $meta : [];
        return array_merge($meta, is_array($meta['app_data'] ?? null) ? $meta['app_data'] : []);
    }

    public function createPending(int $userId, string $type, float $amount, array $metadata = []): string
    {
        if (!in_array($type, ['wallet_funding', 'cv_review', 'candidate_plan', 'course', 'webinar'], true) || !is_finite($amount) || $amount <= 0) {
            throw new \InvalidArgumentException('Invalid purchase.');
        }
        $reference = $type . '_' . bin2hex(random_bytes(16));
        $meta = array_merge($metadata, ['type' => $type, 'user_id' => $userId, 'reference' => $reference]);
        if (!$this->payments->insert(['user_id' => $userId, 'reference' => $reference,
            'amount' => $amount, 'plan_id' => $type === 'candidate_plan' ? ($meta['plan_id'] ?? null) : null, 'currency' => 'NGN', 'status' => 'pending', 'metadata' => json_encode($meta)])) {
            throw new \RuntimeException('Could not create payment order.');
        }
        return $reference;
    }

    public static function validate(array $data, array $order, array $meta, string $email): void
    {
        // Paystack passes its fee to the customer on this account, so the
        // charged amount (data.amount) is the order amount PLUS the fee
        // (data.fees) - e.g. a NGN 100 order is charged as NGN 101.53.
        // Accept either the exact order amount, or the charged amount
        // once the gateway fee is taken back off. All other checks stay
        // exact.
        $expectedKobo = (int) round((float) $order['amount'] * 100);
        $chargedKobo  = (int) ($data['amount'] ?? -1);
        $feeKobo      = (int) ($data['fees'] ?? 0);
        $amountOk     = $chargedKobo === $expectedKobo
            || ($feeKobo > 0 && ($chargedKobo - $feeKobo) === $expectedKobo);
        if ($email === '' || ($data['status'] ?? '') !== 'success'
            || ($data['reference'] ?? '') !== $order['reference']
            || ($data['currency'] ?? '') !== ($order['currency'] ?? 'NGN')
            || !$amountOk
            || (int) ($meta['user_id'] ?? 0) !== (int) $order['user_id']
            || strcasecmp((string) ($data['customer']['email'] ?? ''), $email) !== 0) {
            log_message('error', 'Payment validation mismatch: status=' . ($data['status'] ?? '?')
                . ' charged=' . $chargedKobo . ' expected=' . $expectedKobo . ' fees=' . $feeKobo
                . ' currency=' . ($data['currency'] ?? '?')
                . ' refMatch=' . (($data['reference'] ?? '') === $order['reference'] ? '1' : '0')
                . ' userMatch=' . ((int) ($meta['user_id'] ?? 0) === (int) $order['user_id'] ? '1' : '0')
                . ' emailMatch=' . (strcasecmp((string) ($data['customer']['email'] ?? ''), $email) === 0 ? '1' : '0'));
            throw new \InvalidArgumentException('Payment does not match this order.');
        }
        $expected = self::metadata($order);
        if (($meta['type'] ?? '') !== ($expected['type'] ?? '')
            || (($expected['type'] ?? '') === 'cv_review' && ($meta['plan'] ?? '') !== ($expected['plan'] ?? ''))) {
            throw new \InvalidArgumentException('Payment purchase details do not match.');
        }
        foreach (['plan_id', 'course_id'] as $field) {
            if (isset($expected[$field]) && (int) ($meta[$field] ?? 0) !== (int) $expected[$field]) {
                throw new \InvalidArgumentException('Payment item does not match this order.');
            }
        }
    }

    public static function validateUnlock(array $data, array $order, string $email, int $candidateId): void
    {
        if (empty($order['reference']) || empty($order['user_id'])) {
            throw new \InvalidArgumentException('Profile unlock order not found.');
        }
        $meta = self::metadata($data);
        self::validate($data, $order, $meta, $email);
        $expected = self::metadata($order);
        if (($expected['type'] ?? '') !== 'unlock' || (float)($order['amount'] ?? 0) !== 5000.0
            || ($order['currency'] ?? '') !== 'NGN' || $candidateId < 1
            || (int)($expected['candidate_id'] ?? 0) !== $candidateId
            || (int)($meta['candidate_id'] ?? 0) !== $candidateId) {
            throw new \InvalidArgumentException('Payment does not match this profile unlock.');
        }
    }

    public function fulfill(array $data, ?int $expectedUserId = null, ?string $expectedType = null, ?int $expectedItem = null): array
    {
        if ((self::metadata($data)['type'] ?? '') === 'webinar') {
            if ($expectedType !== null && $expectedType !== 'webinar') throw new \InvalidArgumentException('Payment belongs to a different purchase.');
            return (new WebinarPurchaseService($this->db))->fulfill($data, $expectedUserId, $expectedItem);
        }
        $payments = $this->payments;
        $order = $payments->where('reference', (string) ($data['reference'] ?? ''))->first();
        if (!$order || ($expectedUserId !== null && (int) $order['user_id'] !== $expectedUserId)) {
            throw new \InvalidArgumentException('Payment order not found for this account.');
        }
        $details = self::metadata($order);
        if (($expectedType !== null && ($details['type'] ?? '') !== $expectedType)
            || ($expectedItem !== null && (int) ($details[$expectedType === 'course' ? 'course_id' : 'plan_id'] ?? 0) !== $expectedItem)) {
            throw new \InvalidArgumentException('Payment belongs to a different purchase.');
        }
        $newCourseId = null;
        $result = PurchaseTransaction::run((int) $order['user_id'], function () use ($data, $order, $payments, &$newCourseId) {
            $order = $payments->find($order['id']);
            $identity = $this->db->table('auth_identities')->where('user_id', $order['user_id'])
                ->where('type', 'email_password')->get()->getRow();
            $meta = self::metadata($data);
            self::validate($data, $order, $meta, (string) ($identity->secret ?? ''));
            $type = $meta['type'];
            if (!in_array($type, ['wallet_funding', 'cv_review', 'candidate_plan', 'course'], true)) {
                throw new \InvalidArgumentException('Unsupported payment purchase.');
            }
            $reviewId = null;
            $entitlement = [];
            if ($type === 'cv_review') {
                $review = $this->reviews->where('payment_reference', $order['reference'])->first();
                $reviewId = $review ? (int) $review->id : $this->createCvReview((int) $order['user_id'],
                    (string) $meta['plan'], (float) $order['amount'], $order['reference']);
            } elseif ($type === 'candidate_plan' || $type === 'course') {
                $stored = self::metadata($order);
                $entitlement = $stored['fulfillment'] ?? [];
                if ($order['status'] !== 'paid' || !$entitlement) {
                    $entitlement = $this->activateItem((int) $order['user_id'], $type, (int) $meta[$type === 'course' ? 'course_id' : 'plan_id'], $order['reference'], (float) $order['amount']);
                    if ($type === 'course') $newCourseId = (int) $meta['course_id'];
                }
                $stored['fulfillment'] = $entitlement;
                $payments->update($order['id'], ['metadata' => json_encode($stored)]);
            } else {
                // WalletService checks its own ledger as well as taking a wallet lock.
                (new WalletService($this->db))->credit((int) $order['user_id'], (float) $order['amount'],
                    'paystack_deposit', $order['reference'], null, 'Wallet funded via Paystack.');
            }
            if (!$payments->update($order['id'], ['status' => 'paid', 'amount_paid' => $order['amount'],
                'paid_at' => ($order['paid_at'] ?? null) ?: date('Y-m-d H:i:s'), 'payment_method' => $data['channel'] ?? 'card',
                'channel' => $data['channel'] ?? 'card', 'gateway_response' => json_encode($data)])) {
                throw new \RuntimeException('Could not record payment.');
            }
            return array_merge(['type' => $type, 'review_id' => $reviewId, 'amount' => (float) $order['amount']], $entitlement);
        }, $this->db);
        if ($newCourseId) $this->notifyCourseEnrollment((int) $order['user_id'], $newCourseId);
        return $result;
    }

    public function buyWithWallet(int $userId, string $type, int $itemId, ?string $key = null): array
    {
        $notify = false;
        $result = PurchaseTransaction::run($userId, function () use ($userId, $type, $itemId, $key, &$notify) {
            if (!in_array($type, ['candidate_plan', 'course'], true)) throw new \InvalidArgumentException('Invalid purchase type.');
            $reference = $key ? 'purchase_' . hash('sha256', $userId . ':' . $key) : 'purchase_' . bin2hex(random_bytes(16));
            if ($prior = $this->payments->where('reference', $reference)->first()) {
                $meta = self::metadata($prior);
                if (($meta['type'] ?? '') !== $type || (int) ($meta['item_id'] ?? 0) !== $itemId) {
                    throw new \InvalidArgumentException('Checkout key was used for a different purchase.');
                }
                return array_merge(['reference' => $reference], $meta['fulfillment']);
            }
            $item = $type === 'course' ? (new \App\Models\CourseModel($this->db))->find($itemId)
                : (new \App\Models\PlanModel($this->db))->find($itemId);
            if (!$item || !$item->is_active || ($type === 'candidate_plan' && $item->plan_type !== 'candidate')) {
                throw new \InvalidArgumentException('This purchase is unavailable.');
            }
            if ($type === 'course' && (new \App\Models\CourseEnrollmentModel($this->db))->where('course_id', $itemId)->where('user_id', $userId)->first()) {
                throw new \InvalidArgumentException('You are already enrolled in this course.');
            }
            $amount = (float) ($type === 'course' ? $item->price : $item->base_price);
            if (!is_finite($amount) || $amount < 0) throw new \InvalidArgumentException('Invalid purchase price.');
            // Record the payment FIRST. The payments table is MyISAM on
            // production (no transaction rollback), so the old order - debit,
            // enroll, then insert the payment - left a live enrollment behind
            // when the payment insert failed (e.g. plan_id NOT NULL), while
            // the InnoDB wallet debit rolled back: a free course. If a later
            // step now fails, the payment row is marked 'failed' explicitly
            // instead of standing as 'paid' for an uncompleted purchase.
            $meta = ['type' => $type, 'item_id' => $itemId, 'fulfillment' => []];
            if (! $this->payments->insert(['user_id' => $userId, 'plan_id' => $type === 'candidate_plan' ? $itemId : null,
                'reference' => $reference, 'amount' => $amount, 'amount_paid' => $amount, 'currency' => 'NGN',
                'status' => 'paid', 'payment_method' => 'wallet', 'paid_at' => date('Y-m-d H:i:s'), 'metadata' => json_encode($meta)])) {
                throw new \RuntimeException('Payment could not be recorded.');
            }
            try {
                if ($amount > 0) (new WalletService($this->db))->debit($userId, $amount, $type, $reference, $itemId, 'Platform purchase');
                $entitlement = $this->activateItem($userId, $type, $itemId, $reference, $amount);
            } catch (\Throwable $e) {
                $this->payments->where('reference', $reference)->set(['status' => 'failed'])->update();
                throw $e;
            }
            $meta['fulfillment'] = $entitlement;
            $this->payments->where('reference', $reference)->set(['metadata' => json_encode($meta)])->update();
            $notify = $type === 'course';
            return array_merge(['reference' => $reference], $entitlement);
        }, $this->db);
        if ($notify) $this->notifyCourseEnrollment($userId, $itemId);
        return $result;
    }

    private function notifyCourseEnrollment(int $userId, int $courseId): void
    {
        if (ENVIRONMENT === 'testing') return;
        try {
            $user = model(\App\Models\UserModel::class)->find($userId);
            $course = (new \App\Models\CourseModel($this->db))->find($courseId);
            (new EmailNotificationService())->sendCourseEnrollmentNotification($user, $course);
        } catch (\Throwable $e) {
            log_message('error', 'Course enrollment notification: ' . $e->getMessage());
        }
    }

    private function activateItem(int $userId, string $type, int $itemId, string $reference, float $amount): array
    {
        if ($type === 'course') {
            $courses = new \App\Models\CourseModel($this->db);
            if (!$courses->find($itemId)) throw new \InvalidArgumentException('Course not found.');
            $enrollments = new \App\Models\CourseEnrollmentModel($this->db);
            $existing = $enrollments->where('user_id', $userId)->where('course_id', $itemId)->first();
            if ($existing) return ['enrollment_id' => (int) $existing->id];
            $id = $enrollments->insert(['course_id' => $itemId, 'user_id' => $userId, 'status' => 'enrolled', 'payment_reference' => $reference, 'amount' => $amount]);
            if (!$id) throw new \RuntimeException('Enrollment could not be created.');
            return ['enrollment_id' => (int) $enrollments->getInsertID()];
        }
        $plan = (new \App\Models\PlanModel($this->db))->find($itemId);
        if (!$plan || $plan->plan_type !== 'candidate') throw new \InvalidArgumentException('Invalid candidate plan.');
        $tiers = is_string($plan->pricing_tiers) ? json_decode($plan->pricing_tiers, true) : $plan->pricing_tiers;
        $duration = is_array($tiers[1] ?? null) ? max(1, min(366, (int) ($tiers[1]['duration'] ?? 30))) : 30;
        $subscriptions = new \App\Models\UserSubscriptionModel($this->db);
        $subscriptions->where('user_id', $userId)->where('is_active', 1)->set(['is_active' => 0])->update();
        $id = $subscriptions->insert(['user_id' => $userId, 'plan_id' => $itemId, 'starts_at' => date('Y-m-d H:i:s'),
            'ends_at' => date('Y-m-d H:i:s', strtotime('+' . $duration . ' days')), 'is_active' => 1, 'auto_renew' => 0]);
        if (!$id) throw new \RuntimeException('Subscription could not be activated.');
        return ['subscription_id' => (int) $subscriptions->getInsertID()];
    }

    public function createCvReview(int $userId, string $plan, float $amount, string $reference): int
    {
        if (!in_array($plan, ['professional', 'premium'], true) || !is_finite($amount) || $amount < 0) {
            throw new \InvalidArgumentException('Invalid CV review plan.');
        }
        $id = $this->reviews->insert(['user_id' => $userId, 'plan' => $plan,
            'amount' => $amount, 'payment_reference' => $reference, 'payment_status' => 'paid',
            'status' => 'pending', 'review_mode' => env('cv_review_mode', 'semi') === 'auto' ? 'auto' : 'semi']);
        if (!$id) {
            throw new \RuntimeException('Could not create CV review.');
        }
        return (int) $this->reviews->getInsertID();
    }
}
