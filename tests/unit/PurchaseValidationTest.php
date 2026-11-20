<?php

use App\Services\PaymentFulfillmentService;
use App\Services\PaystackService;
use App\Services\SubscriptionService;
use CodeIgniter\Test\CIUnitTestCase;

final class PurchaseValidationTest extends CIUnitTestCase
{
    private function order(): array
    {
        return ['reference' => 'cv_order', 'amount' => 15000, 'currency' => 'NGN', 'user_id' => 7,
            'metadata' => json_encode(['type' => 'cv_review', 'plan' => 'professional'])];
    }

    private function transaction(): array
    {
        return ['reference' => 'cv_order', 'status' => 'success', 'amount' => 1500000,
            'currency' => 'NGN', 'customer' => ['email' => 'owner@example.com'],
            'metadata' => ['type' => 'cv_review', 'plan' => 'professional', 'user_id' => 7]];
    }

    public function testMatchingOrderIsAccepted(): void
    {
        $data = $this->transaction();
        PaymentFulfillmentService::validate($data, $this->order(), $data['metadata'], 'owner@example.com');
        $this->assertTrue(true);
    }

    public function testUnrelatedPaymentsCannotUnlockAReview(): void
    {
        foreach (['reference' => 'other', 'currency' => 'USD', 'amount' => 1, 'status' => 'failed',
            'customer' => ['email' => 'other@example.com'],
            'metadata' => ['type' => 'course', 'plan' => 'professional', 'user_id' => 7]] as $key => $value) {
            $data = array_replace($this->transaction(), [$key => $value]);
            try {
                PaymentFulfillmentService::validate($data, $this->order(), $data['metadata'], 'owner@example.com');
                $this->fail('Accepted mismatched ' . $key);
            } catch (InvalidArgumentException $e) {
                $this->assertNotEmpty($e->getMessage());
            }
        }
    }

    public function testWrongUserAndWrongPlanAreRejected(): void
    {
        foreach ([['user_id' => 8], ['plan' => 'premium']] as $change) {
            $data = $this->transaction();
            $meta = array_replace($data['metadata'], $change);
            try {
                PaymentFulfillmentService::validate($data, $this->order(), $meta, 'owner@example.com');
                $this->fail('Accepted mismatched purchase metadata.');
            } catch (InvalidArgumentException $e) {
                $this->assertNotEmpty($e->getMessage());
            }
        }
    }

    public function testMissingCredentialsNeverSimulateSuccess(): void
    {
        $service = (new ReflectionClass(PaystackService::class))->newInstanceWithoutConstructor();
        $this->assertFalse($service->initialize('owner@example.com', 100, '/callback')['status']);
        $this->assertFalse($service->verify('invented_reference')['status']);
    }

    public function testOnlyConfiguredPositiveBillingPeriodsAreAccepted(): void
    {
        $this->assertSame(500.0, SubscriptionService::priceForDuration(3, 200, [1 => 200, 3 => 500]));
        foreach ([-12, 0, 2, 37, 99999] as $months) {
            try {
                SubscriptionService::priceForDuration($months, 200, [1 => 200, 3 => 500]);
                $this->fail('Accepted unsupported duration.');
            } catch (InvalidArgumentException $e) {
                $this->assertNotEmpty($e->getMessage());
            }
        }
    }

    public function testUnrelatedPaymentsDoNotCreateUpgradeCredit(): void
    {
        $payment = ['plan_id' => 3, 'status' => 'paid', 'amount' => 100000,
            'paid_at' => '2026-10-01 12:00:00', 'metadata' => ['type' => 'wallet_funding']];
        $this->assertNull(SubscriptionService::subscriptionPaymentValue($payment, 9, 3, '2026-10-01 12:00:00'));
        $payment['metadata'] = ['type' => 'subscription', 'subscription_id' => 8];
        $this->assertNull(SubscriptionService::subscriptionPaymentValue($payment, 9, 3, '2026-10-01 12:00:00'));
        $payment['metadata'] = ['type' => 'subscription', 'subscription_id' => 9, 'subscription_value' => 2000];
        $this->assertSame(2000.0, SubscriptionService::subscriptionPaymentValue($payment, 9, 3, '2026-10-01 12:00:00'));
        $this->assertNull(SubscriptionService::subscriptionPaymentValue($payment, 9, 4, '2026-10-01 12:00:00'));
    }
}
