<?php

use App\Services\PaymentFulfillmentService;
use App\Services\PurchaseTransaction;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;

final class PaymentFulfillmentRegressionTest extends CIUnitTestCase
{
    private $connection;
    private PaymentFulfillmentService $fulfillment;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = Database::connect(config('Database')->tests, false);
        foreach ([
            'CREATE TABLE users (id INTEGER PRIMARY KEY)',
            'CREATE TABLE auth_identities (user_id INTEGER, type TEXT, secret TEXT)',
            'CREATE TABLE payments (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, plan_id INTEGER, reference TEXT UNIQUE, amount REAL, currency TEXT, status TEXT, metadata TEXT, paid_at TEXT, payment_method TEXT, amount_paid REAL, channel TEXT, gateway_response TEXT, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE wallets (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER UNIQUE, balance REAL, is_locked INTEGER DEFAULT 0, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE wallet_transactions (id INTEGER PRIMARY KEY AUTOINCREMENT, wallet_id INTEGER, type TEXT, amount REAL, reference TEXT UNIQUE, description TEXT)',
            'CREATE TABLE plans (id INTEGER PRIMARY KEY, name TEXT, plan_type TEXT, base_price REAL, pricing_tiers TEXT, is_active INTEGER, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE user_subscriptions (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, plan_id INTEGER, starts_at TEXT, ends_at TEXT, is_active INTEGER, auto_renew INTEGER, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE courses (id INTEGER PRIMARY KEY, title TEXT, price REAL, is_active INTEGER)',
            'CREATE TABLE course_enrollments (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, course_id INTEGER, status TEXT, payment_reference TEXT, amount REAL, created_at TEXT)',
            'CREATE TABLE cv_reviews (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, plan TEXT, amount REAL, payment_reference TEXT, payment_status TEXT, status TEXT, review_mode TEXT, file_path TEXT, created_at TEXT, updated_at TEXT)',
        ] as $sql) {
            $this->connection->query($sql);
        }
        $this->connection->table('users')->insert(['id' => 7]);
        $this->connection->table('auth_identities')->insert(['user_id' => 7, 'type' => 'email_password', 'secret' => 'owner@example.com']);
        $this->fulfillment = new PaymentFulfillmentService($this->connection);
    }

    protected function tearDown(): void
    {
        $this->connection->close();
        parent::tearDown();
    }

    private function verified(string $reference, string $type, int $amount): array
    {
        return ['reference' => $reference, 'status' => 'success', 'amount' => $amount,
            'currency' => 'NGN', 'customer' => ['email' => 'owner@example.com'],
            'metadata' => ['type' => $type, 'plan' => 'professional', 'user_id' => 7]];
    }

    public function testWebhookAndCallbackCreditWalletOnlyOnce(): void
    {
        $ref = $this->fulfillment->createPending(7, 'wallet_funding', 1000);
        $data = $this->verified($ref, 'wallet_funding', 100000);
        $this->fulfillment->fulfill($data);
        $this->fulfillment->fulfill($data, 7);
        $this->assertSame(1000.0, (float) $this->connection->table('wallets')->get()->getRow()->balance);
        $this->assertSame(1, $this->connection->table('wallet_transactions')->countAllResults());
        $this->assertSame('paid', $this->connection->table('payments')->get()->getRow()->status);
    }

    public function testReplayedCvPaymentReturnsTheSameReview(): void
    {
        $ref = $this->fulfillment->createPending(7, 'cv_review', 15000, ['plan' => 'professional']);
        $data = $this->verified($ref, 'cv_review', 1500000);
        $first = $this->fulfillment->fulfill($data, 7);
        $second = $this->fulfillment->fulfill($data);
        $this->assertSame($first['review_id'], $second['review_id']);
        $this->assertSame(1, $this->connection->table('cv_reviews')->countAllResults());
    }

    public function testWrongOwnerCannotClaimPayment(): void
    {
        $ref = $this->fulfillment->createPending(7, 'cv_review', 15000, ['plan' => 'professional']);
        $this->expectException(InvalidArgumentException::class);
        $this->fulfillment->fulfill($this->verified($ref, 'cv_review', 1500000), 8);
    }

    public function testUploadAuthorizationCannotBeBypassedWithBasicPlan(): void
    {
        $id = $this->fulfillment->createCvReview(7, 'professional', 15000, 'owned_review');
        $reviews = new \App\Models\CvReviewModel($this->connection);
        $this->assertSame('professional', $reviews->findForUpload($id, 7)->plan);
        $this->expectException(InvalidArgumentException::class);
        // Plan is read from the persisted record; callers cannot substitute basic.
        $reviews->findForUpload($id, 8);
    }

    public function testAnUnpaidReviewCannotAcceptAnUpload(): void
    {
        $id = $this->fulfillment->createCvReview(7, 'premium', 30000, 'unpaid_review');
        $reviews = new \App\Models\CvReviewModel($this->connection);
        $reviews->update($id, ['payment_status' => 'pending']);
        $this->expectException(InvalidArgumentException::class);
        $reviews->findForUpload($id, 7);
    }

    public function testSubmittedReviewCannotBeReused(): void
    {
        $id = $this->fulfillment->createCvReview(7, 'premium', 30000, 'submitted_review');
        $reviews = new \App\Models\CvReviewModel($this->connection);
        $reviews->update($id, ['file_path' => 'uploads/review.pdf']);
        $this->expectException(InvalidArgumentException::class);
        $reviews->findForUpload($id, 7);
    }

    public function testFailedEntitlementCreationRollsBackWalletDebit(): void
    {
        $this->connection->table('wallets')->insert(['user_id' => 7, 'balance' => 20000]);
        try {
            PurchaseTransaction::run(7, function () {
                (new \App\Services\WalletService($this->connection))->debit(7, 15000, 'cv_review', 'failed_purchase');
                throw new RuntimeException('Entitlement creation failed');
            }, $this->connection);
            $this->fail('Expected purchase failure.');
        } catch (RuntimeException $e) {
            $this->assertSame('Entitlement creation failed', $e->getMessage());
        }
        $this->assertSame(20000.0, (float) $this->connection->table('wallets')->get()->getRow()->balance);
        $this->assertSame(0, $this->connection->table('wallet_transactions')->countAllResults());
    }
    public function testCandidateWalletPurchaseRecordsOnePaymentAndSubscription(): void
    {
        $this->connection->table('plans')->insert(['id' => 3, 'name' => 'Candidate Pro', 'plan_type' => 'candidate', 'base_price' => 5000, 'is_active' => 1]);
        $this->connection->table('wallets')->insert(['user_id' => 7, 'balance' => 10000]);
        $first = $this->fulfillment->buyWithWallet(7, 'candidate_plan', 3, 'same-checkout');
        $second = $this->fulfillment->buyWithWallet(7, 'candidate_plan', 3, 'same-checkout');
        $this->assertSame($first, $second);
        $this->assertSame(1, $this->connection->table('payments')->countAllResults());
        $this->assertSame(1, $this->connection->table('user_subscriptions')->countAllResults());
        $this->assertSame(5000.0, (float) $this->connection->table('wallets')->get()->getRow()->balance);
    }

    public function testCoursePaymentCannotBeAppliedToAnotherCourse(): void
    {
        $ref = $this->fulfillment->createPending(7, 'course', 2000, ['course_id' => 3]);
        $data = $this->verified($ref, 'course', 200000);
        $data['metadata']['course_id'] = 3;
        $this->expectException(InvalidArgumentException::class);
        $this->fulfillment->fulfill($data, 7, 'course', 4);
    }

    public function testPaidCourseWalletCheckoutRecordsAmountAndDebitsOnce(): void
    {
        $this->connection->table('courses')->insert(['id' => 8, 'title' => 'Paid Course', 'price' => 8500, 'is_active' => 1]);
        $this->connection->table('wallets')->insert(['user_id' => 7, 'balance' => 10000]);
        $first = $this->fulfillment->buyWithWallet(7, 'course', 8, 'course-checkout');
        $second = $this->fulfillment->buyWithWallet(7, 'course', 8, 'course-checkout');
        $this->assertSame($first, $second);
        $this->assertSame(1500.0, (float) $this->connection->table('wallets')->get()->getRow()->balance);
        $this->assertSame(8500.0, (float) $this->connection->table('course_enrollments')->get()->getRow()->amount);
        $this->assertSame('wallet', $this->connection->table('payments')->get()->getRow()->payment_method);
        $this->assertSame(1, $this->connection->table('wallet_transactions')->countAllResults());
    }

    public function testCoursePaymentReplayOnlyEnrollsOnce(): void
    {
        $this->connection->table('courses')->insert(['id' => 3, 'title' => 'Course', 'price' => 2000, 'is_active' => 1]);
        $ref = $this->fulfillment->createPending(7, 'course', 2000, ['course_id' => 3]);
        $data = $this->verified($ref, 'course', 200000);
        $data['metadata']['course_id'] = 3;
        $this->fulfillment->fulfill($data, 7, 'course', 3);
        $this->fulfillment->fulfill($data, 7, 'course', 3);
        $this->assertSame(1, $this->connection->table('course_enrollments')->countAllResults());
        $this->assertSame(2000.0, (float) $this->connection->table('course_enrollments')->get()->getRow()->amount);
    }

    public function testCandidatePaymentCannotSelectAnotherPlan(): void
    {
        $ref = $this->fulfillment->createPending(7, 'candidate_plan', 2000, ['plan_id' => 3]);
        $data = $this->verified($ref, 'candidate_plan', 200000);
        $data['metadata']['plan_id'] = 4;
        $this->expectException(InvalidArgumentException::class);
        $this->fulfillment->fulfill($data, 7, 'candidate_plan');
    }

}
