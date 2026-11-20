<?php

use App\Services\CreditService;
use App\Services\EmployerSubscriptionPaymentService;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;

final class EmployerSubscriptionRegressionTest extends CIUnitTestCase
{
    private $connection;
    private EmployerSubscriptionPaymentService $payments;
    private array $previousConnections;

    protected function setUp(): void
    {
        parent::setUp();
        $settings = $this->createMock(\CodeIgniter\Settings\Settings::class);
        $settings->method('get')->willReturn(false);
        \Config\Services::injectMock('settings', $settings);
        $this->connection = Database::connect(config('Database')->tests, false);
        $this->previousConnections = Database::getConnections();
        (new ReflectionProperty(\CodeIgniter\Database\Config::class, 'instances'))->setValue(null,
            array_replace($this->previousConnections, ['tests' => $this->connection]));
        foreach ([
            'CREATE TABLE users (id INTEGER PRIMARY KEY)',
            'CREATE TABLE auth_identities (user_id INTEGER, type TEXT, secret TEXT)',
            'CREATE TABLE employers (id INTEGER PRIMARY KEY, user_id INTEGER, unlimited_access INTEGER DEFAULT 0, unlimited_until TEXT, parent_employer_id INTEGER)',
            'CREATE TABLE payments (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, employer_id INTEGER, plan_id INTEGER, reference TEXT UNIQUE, amount REAL, amount_paid REAL, currency TEXT, status TEXT, metadata TEXT, paid_at TEXT, payment_method TEXT, gateway_response TEXT, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE plans (id INTEGER PRIMARY KEY, code TEXT, name TEXT, plan_type TEXT, base_price REAL, pricing_tiers TEXT, features TEXT, monthly_job_credits INTEGER, is_active INTEGER, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE user_subscriptions (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, plan_id INTEGER, starts_at TEXT, ends_at TEXT, is_active INTEGER, auto_renew INTEGER, subscription_code TEXT, email_token TEXT, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE job_credit_wallets (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, credits INTEGER, source TEXT, reference_id INTEGER, expires_at TEXT, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE job_credit_transactions (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, type TEXT, credits INTEGER, reference TEXT, description TEXT, meta TEXT, created_at TEXT, updated_at TEXT)',
        ] as $sql) $this->connection->query($sql);
        $this->connection->table('users')->insert(['id' => 7]);
        $this->connection->table('auth_identities')->insert(['user_id' => 7, 'type' => 'email_password', 'secret' => 'owner@example.com']);
        $this->connection->table('employers')->insert(['id' => 3, 'user_id' => 7]);
        $this->connection->table('plans')->insert(['id' => 1, 'code' => 'business_pro', 'name' => 'Business Pro',
            'plan_type' => 'subscription', 'base_price' => 18000, 'pricing_tiers' => '{"1":18000,"3":48000}',
            'features' => '{}', 'monthly_job_credits' => 0, 'is_active' => 1]);
        $this->payments = new EmployerSubscriptionPaymentService($this->connection);
    }

    protected function tearDown(): void
    {
        $this->connection->close();
        (new ReflectionProperty(\CodeIgniter\Database\Config::class, 'instances'))->setValue(null, $this->previousConnections);
        parent::tearDown();
    }

    private function charge(string $reference = 'paid-month', int $months = 1, int $amount = 1800000): array
    {
        return ['reference' => $reference, 'status' => 'success', 'currency' => 'NGN', 'amount' => $amount,
            'customer' => ['email' => 'owner@example.com'], 'metadata' => ['app_data' => [
                'type' => 'subscription', 'user_id' => 7, 'plan_id' => 1, 'months' => $months]]];
    }

    private function credits(): CreditService
    {
        $service = (new ReflectionClass(CreditService::class))->newInstanceWithoutConstructor();
        foreach (['walletModel' => \App\Models\JobCreditWalletModel::class,
            'transactionModel' => \App\Models\JobCreditTransactionModel::class,
            'subscriptionModel' => \App\Models\UserSubscriptionModel::class,
            'planModel' => \App\Models\PlanModel::class, 'employerModel' => \App\Models\EmployerModel::class] as $property => $class) {
            (new ReflectionProperty($service, $property))->setValue($service, new $class($this->connection));
        }
        return $service;
    }

    public function testBusinessProWithZeroNumericCreditsCanPostAndRepostWithoutDeduction(): void
    {
        $this->payments->fulfill($this->charge(), 7);
        $credits = $this->credits();
        $this->assertSame(0, $this->connection->table('job_credit_wallets')->countAllResults());
        $this->assertTrue($credits->hasUnlimitedAccess(7));
        $this->assertTrue($credits->canPerformAction(7)['unlimited']);
        $this->assertTrue($credits->deductCredits(7, 1, 'repost', 'Repost job')['unlimited']);
        $this->assertTrue($credits->getCreditSummary(7)['has_unlimited']);
    }

    public function testWebhookAndCallbackOnlyActivateAndGrantOnce(): void
    {
        $this->connection->table('plans')->where('id', 1)->update(['code' => 'monthly_search', 'name' => 'Monthly Search', 'monthly_job_credits' => 10]);
        $first = $this->payments->fulfill($this->charge());
        $second = $this->payments->fulfill($this->charge(), 7);
        $this->assertSame($first['subscription_id'], $second['subscription_id']);
        $this->assertTrue($second['already_processed']);
        $this->assertSame(1, $this->connection->table('user_subscriptions')->countAllResults());
        $this->assertSame(10, (int) $this->connection->table('job_credit_wallets')->get()->getRow()->credits);
        $this->assertSame(1, $this->connection->table('job_credit_transactions')->countAllResults());
        $this->assertSame(10, $this->credits()->getAvailableCredits(7));
        $this->assertFalse($this->credits()->hasUnlimitedAccess(7));
        $this->assertTrue($this->credits()->canPerformAction(7)['can']);
        $this->connection->table('job_credit_wallets')->update(['expires_at' => date('Y-m-d H:i:s', time() - 60)]);
        $this->assertSame(0, $this->credits()->getAvailableCredits(7));
    }

    public function testExpiredAndFutureSubscriptionsDoNotGrantUnlimitedAccess(): void
    {
        $result = $this->payments->fulfill($this->charge());
        $this->connection->table('user_subscriptions')->where('id', $result['subscription_id'])->update(['ends_at' => date('Y-m-d H:i:s', time() - 60)]);
        $this->assertFalse($this->credits()->hasUnlimitedAccess(7));
        $this->connection->table('user_subscriptions')->where('id', $result['subscription_id'])->update([
            'starts_at' => date('Y-m-d H:i:s', time() + 60), 'ends_at' => date('Y-m-d H:i:s', time() + 3600)]);
        $this->assertFalse($this->credits()->hasUnlimitedAccess(7));
    }

    public function testWrongPayerCannotActivate(): void
    {
        $data = $this->charge();
        $data['customer']['email'] = 'someone@example.com';
        try { $this->payments->fulfill($data); $this->fail('Wrong payer accepted'); }
        catch (InvalidArgumentException $e) { $this->assertSame(0, $this->connection->table('user_subscriptions')->countAllResults()); }
    }

    public function testWrongLoggedInAccountCannotClaimPayment(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->payments->fulfill($this->charge(), 99);
    }

    public function testUnderpaymentDoesNotActivate(): void
    {
        $this->expectException(InvalidArgumentException::class);
        $this->payments->fulfill($this->charge(amount: 100));
    }

    public function testPaidRecordWithoutActivationCannotReportSuccess(): void
    {
        $data = $this->charge();
        $this->connection->table('payments')->insert(['user_id' => 7, 'plan_id' => 1, 'reference' => 'paid-month',
            'amount' => 18000, 'currency' => 'NGN', 'status' => 'paid', 'paid_at' => date('Y-m-d H:i:s'),
            'metadata' => json_encode($data['metadata']['app_data'])]);
        $this->expectException(RuntimeException::class);
        $this->payments->fulfill($data);
    }

    public function testSavedProrationOrderActivatesAtItsAgreedPrice(): void
    {
        $data = $this->charge(amount: 900000);
        $meta = $data['metadata']['app_data'];
        $meta['proration'] = ['new_plan_price' => 18000];
        $this->connection->table('payments')->insert(['user_id' => 7, 'plan_id' => 1, 'reference' => 'paid-month',
            'amount' => 9000, 'currency' => 'NGN', 'status' => 'pending', 'metadata' => json_encode($meta)]);
        $this->payments->fulfill($data);
        $payment = $this->connection->table('payments')->get()->getRow();
        $this->assertSame('paid', $payment->status);
        $this->assertSame(18000, json_decode($payment->metadata, true)['subscription_value']);
        $this->assertSame(1, $this->connection->table('payments')->countAllResults());
    }

    public function testDurationCannotBeChangedAfterCheckout(): void
    {
        $data = $this->charge(months: 3, amount: 4800000);
        $this->connection->table('payments')->insert(['user_id' => 7, 'plan_id' => 1, 'reference' => 'paid-month',
            'amount' => 48000, 'currency' => 'NGN', 'status' => 'pending', 'metadata' => json_encode([
                'type' => 'subscription', 'user_id' => 7, 'plan_id' => 1, 'months' => 1])]);
        $this->expectException(InvalidArgumentException::class);
        $this->payments->fulfill($data);
    }

    public function testCreditFailureRollsBackPaymentAndActivation(): void
    {
        $this->connection->table('plans')->where('id', 1)->update(['monthly_job_credits' => 10]);
        $this->connection->query("CREATE TRIGGER reject_credits BEFORE INSERT ON job_credit_wallets BEGIN SELECT RAISE(ABORT, 'test failure'); END");
        try { $this->payments->fulfill($this->charge()); $this->fail('Expected credit failure'); }
        catch (Throwable $e) {
            $this->assertSame(0, $this->connection->table('user_subscriptions')->countAllResults());
            $this->assertSame(0, $this->connection->table('payments')->countAllResults());
        }
    }

    public function testRepairAllocatesOnlyOnceAndNeverRefillsSpentCredits(): void
    {
        $this->connection->table('plans')->where('id', 1)->update(['code' => 'finite', 'name' => 'Finite', 'monthly_job_credits' => 10]);
        $this->connection->table('user_subscriptions')->insert(['user_id' => 7, 'plan_id' => 1,
            'starts_at' => date('Y-m-d H:i:s'), 'ends_at' => date('Y-m-d H:i:s', strtotime('+1 month')), 'is_active' => 1]);
        ob_start();
        try {
            $command = new \App\Commands\FixMissingCredits(service('logger'), service('commands'));
            $command->run([]);
            $command->run([]);
            $this->assertSame(10, (int) $this->connection->table('job_credit_wallets')->get()->getRow()->credits);
            $this->connection->table('job_credit_wallets')->update(['credits' => 0]);
            $command->run([]);
            $this->assertSame(0, (int) $this->connection->table('job_credit_wallets')->get()->getRow()->credits);
            $this->assertSame(1, $this->connection->table('job_credit_transactions')->countAllResults());
        } finally { ob_end_clean(); }
    }

    public function testMaintenanceNeverRenewsAnUnpaidPeriod(): void
    {
        $this->connection->table('user_subscriptions')->insert(['user_id' => 7, 'plan_id' => 1,
            'starts_at' => date('Y-m-d H:i:s', strtotime('-2 months')),
            'ends_at' => date('Y-m-d H:i:s', strtotime('-1 month')), 'is_active' => 1]);
        ob_start();
        try {
            (new \App\Commands\RenewSubscriptions(service('logger'), service('commands')))->run([]);
            $this->assertSame(0, (int) $this->connection->table('user_subscriptions')->get()->getRow()->is_active);
            $this->assertSame(0, $this->connection->table('job_credit_wallets')->countAllResults());
        } finally { ob_end_clean(); }
    }

    public function testCreditGrantRollsBackWhenLedgerWriteFails(): void
    {
        $this->connection->query("CREATE TRIGGER reject_grant_ledger BEFORE INSERT ON job_credit_transactions BEGIN SELECT RAISE(ABORT, 'ledger failure'); END");
        try {
            (new CreditService($this->connection))->addCredits(7, 5, 'bundle', 12);
            $this->fail('Grant succeeded without its ledger record');
        } catch (\CodeIgniter\Database\Exceptions\DatabaseException | RuntimeException $e) {
            $this->assertSame(0, $this->connection->table('job_credit_wallets')->countAllResults());
        }
    }

    public function testDeductionUsesExpiringSubscriptionBeforePermanentBundleCredits(): void
    {
        $credits = new CreditService($this->connection);
        $credits->addCredits(7, 5, 'bundle', 12);
        $credits->addCredits(7, 2, 'subscription', 13, date('Y-m-d H:i:s', strtotime('+1 day')));
        $result = $credits->deductCredits(7, 3, 'post-job-1', 'Post job');
        $this->assertTrue($result['success']);
        $this->assertSame(4, $result['remaining']);
        $this->assertSame(['subscription', 'bundle'], array_column($result['deducted_from'], 'source'));
    }

    public function testDeductionRetryOnlySpendsCreditsOnceEvenWhenBalanceIsEmpty(): void
    {
        $credits = new CreditService($this->connection);
        $credits->addCredits(7, 2, 'bundle', 12);
        $first = $credits->deductCredits(7, 2, 'post-job-1', 'Post job');
        $second = $credits->deductCredits(7, 2, 'post-job-1', 'Post job');
        $this->assertTrue($first['success']);
        $this->assertTrue($second['success']);
        $this->assertTrue($second['already_processed']);
        $this->assertSame(0, $credits->getAvailableCredits(7));
        $this->assertSame(1, $this->connection->table('job_credit_transactions')->where('type', 'credit_out')->countAllResults());
    }

    public function testDeductionReferenceCannotBeReusedWithADifferentAmount(): void
    {
        $credits = new CreditService($this->connection);
        $credits->addCredits(7, 5, 'bundle', 12);
        $this->assertTrue($credits->deductCredits(7, 2, 'post-job-1', 'Post job')['success']);
        $this->assertFalse($credits->deductCredits(7, 1, 'post-job-1', 'Post job')['success']);
        $this->assertSame(3, $credits->getAvailableCredits(7));
    }

    public function testFailedDeductionRollsBackWalletBalance(): void
    {
        $credits = new CreditService($this->connection);
        $credits->addCredits(7, 5, 'bundle', 12);
        $this->connection->query("CREATE TRIGGER reject_deduction_ledger BEFORE INSERT ON job_credit_transactions WHEN NEW.type = 'credit_out' BEGIN SELECT RAISE(ABORT, 'ledger failure'); END");
        $this->assertFalse($credits->deductCredits(7, 2, 'post-job-1', 'Post job')['success']);
        $this->assertSame(5, $credits->getAvailableCredits(7));
        $this->assertSame(0, $this->connection->table('job_credit_transactions')->where('type', 'credit_out')->countAllResults());
    }

    public function testInsufficientDeductionRollsBackPartialWalletSpending(): void
    {
        $credits = new CreditService($this->connection);
        $credits->addCredits(7, 1, 'bundle', 12);
        $this->assertFalse($credits->deductCredits(7, 2, 'post-job-1', 'Post job')['success']);
        $this->assertSame(1, $credits->getAvailableCredits(7));
    }

    public function testZeroAndNegativeDeductionsAreRejected(): void
    {
        $credits = new CreditService($this->connection);
        $credits->addCredits(7, 5, 'bundle', 12);
        foreach ([0, -2] as $amount) $this->assertFalse($credits->deductCredits(7, $amount, 'post-job-1', 'Post job')['success']);
        $this->assertSame(5, $credits->getAvailableCredits(7));
        $this->assertSame(0, $this->connection->table('job_credit_transactions')->where('type', 'credit_out')->countAllResults());
    }

    public function testGatewayReferenceDoesNotEnterIntegerWalletLink(): void
    {
        $credits = new CreditService($this->connection);
        $credits->addCredits(7, 5, 'subscription', 12, null, 'wallet_pay_order_123');
        $wallet = $this->connection->table('job_credit_wallets')->get()->getRow();
        $ledger = $this->connection->table('job_credit_transactions')->get()->getRow();
        $this->assertSame(12, (int) $wallet->reference_id);
        $this->assertSame('wallet_pay_order_123', $ledger->reference);
    }
}
