<?php

use App\Services\BundleService;
use CodeIgniter\Test\CIUnitTestCase;
use Config\Database;

final class BundlePaymentRegressionTest extends CIUnitTestCase
{
    private $connection;
    private BundleService $bundles;

    protected function setUp(): void
    {
        parent::setUp();
        $this->connection = Database::connect(config('Database')->tests, false);
        foreach ([
            'CREATE TABLE users (id INTEGER PRIMARY KEY)',
            'CREATE TABLE auth_identities (user_id INTEGER, type TEXT, secret TEXT)',
            'CREATE TABLE plan_bundles (id INTEGER PRIMARY KEY, name TEXT, slug TEXT, job_credits INTEGER, price REAL, is_active INTEGER, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE payments (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, reference TEXT UNIQUE, amount REAL, amount_paid REAL, currency TEXT, status TEXT, metadata TEXT, paid_at TEXT, payment_method TEXT, gateway_response TEXT, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE wallets (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER UNIQUE, balance REAL, is_locked INTEGER DEFAULT 0, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE wallet_transactions (id INTEGER PRIMARY KEY AUTOINCREMENT, wallet_id INTEGER, type TEXT, amount REAL, reference TEXT UNIQUE, description TEXT)',
            'CREATE TABLE job_credit_wallets (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, credits INTEGER, source TEXT, reference_id INTEGER, expires_at TEXT, created_at TEXT, updated_at TEXT)',
            'CREATE TABLE job_credit_transactions (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, type TEXT, credits INTEGER, reference TEXT, description TEXT, meta TEXT, created_at TEXT, updated_at TEXT)',
        ] as $sql) $this->connection->query($sql);
        $this->connection->table('users')->insertBatch([['id' => 7], ['id' => 8]]);
        $this->connection->table('auth_identities')->insert(['user_id' => 7, 'type' => 'email_password', 'secret' => 'payer@example.com']);
        $this->connection->table('plan_bundles')->insertBatch([
            ['id' => 1, 'name' => 'Starter', 'slug' => 'starter', 'job_credits' => 5, 'price' => 10000, 'is_active' => 1],
            ['id' => 2, 'name' => 'Growth', 'slug' => 'growth', 'job_credits' => 10, 'price' => 18000, 'is_active' => 1],
        ]);
        $this->connection->table('wallets')->insert(['user_id' => 7, 'balance' => 5000]);
        $this->bundles = new BundleService($this->connection);
    }

    protected function tearDown(): void
    {
        $this->connection->close();
        parent::tearDown();
    }

    private function charge(array $order): array
    {
        return ['reference' => $order['reference'], 'status' => 'success', 'currency' => 'NGN',
            'amount' => (int) round($order['amount'] * 100), 'customer' => ['email' => 'payer@example.com'],
            'metadata' => ['app_data' => $order['metadata']]];
    }

    public function testNestedMetadataWebhookAndCallbackFulfillOnlyOnce(): void
    {
        $order = $this->bundles->createPending(7, 1, 3000);
        $data = $this->charge($order);
        $this->assertFalse($this->bundles->fulfill($data)['already_processed']);
        $this->assertTrue($this->bundles->fulfill($data, 7)['already_processed']);
        $this->assertSame(2000.0, (float) $this->connection->table('wallets')->get()->getRow()->balance);
        $this->assertSame(1, $this->connection->table('wallet_transactions')->countAllResults());
        $this->assertSame(1, $this->connection->table('job_credit_wallets')->countAllResults());
        $this->assertSame(5, (int) $this->connection->table('job_credit_wallets')->get()->getRow()->credits);
        $this->assertSame('paid', $this->connection->table('payments')->get()->getRow()->status);
    }

    public function testInsufficientWalletFundsDoNotGrantCredits(): void
    {
        $data = $this->charge($this->bundles->createPending(7, 1, 7000));
        try { $this->bundles->fulfill($data); $this->fail('Insufficient payment accepted'); }
        catch (RuntimeException $e) {
            $this->assertSame(0, $this->connection->table('job_credit_wallets')->countAllResults());
            $this->assertSame('pending', $this->connection->table('payments')->get()->getRow()->status);
            $this->assertSame(5000.0, (float) $this->connection->table('wallets')->get()->getRow()->balance);
        }
    }

    public function testLedgerFailureRollsBackWalletDebitAndCreditGrant(): void
    {
        $data = $this->charge($this->bundles->createPending(7, 1, 3000));
        $this->connection->query("CREATE TRIGGER reject_bundle_ledger BEFORE INSERT ON job_credit_transactions BEGIN SELECT RAISE(ABORT, 'ledger failure'); END");
        try { $this->bundles->fulfill($data); $this->fail('Broken ledger accepted'); }
        catch (\CodeIgniter\Database\Exceptions\DatabaseException | RuntimeException $e) {
            $this->assertSame(5000.0, (float) $this->connection->table('wallets')->get()->getRow()->balance);
            $this->assertSame(0, $this->connection->table('wallet_transactions')->countAllResults());
            $this->assertSame(0, $this->connection->table('job_credit_wallets')->countAllResults());
            $this->assertSame('pending', $this->connection->table('payments')->get()->getRow()->status);
        }
    }

    public function testUnderpaymentIsRejected(): void
    {
        $data = $this->charge($this->bundles->createPending(7, 1));
        $data['amount'] = 100;
        $this->expectException(InvalidArgumentException::class);
        $this->bundles->fulfill($data);
    }

    public function testWrongLoggedInUserCannotClaimPayment(): void
    {
        $data = $this->charge($this->bundles->createPending(7, 1));
        $this->expectException(InvalidArgumentException::class);
        $this->bundles->fulfill($data, 8);
    }

    public function testWrongPayerIsRejected(): void
    {
        $data = $this->charge($this->bundles->createPending(7, 1));
        $data['customer']['email'] = 'other@example.com';
        $this->expectException(InvalidArgumentException::class);
        $this->bundles->fulfill($data);
    }

    public function testBundleCannotBeChangedAfterCheckout(): void
    {
        $data = $this->charge($this->bundles->createPending(7, 1));
        $data['metadata']['app_data']['bundle_id'] = 2;
        $this->expectException(InvalidArgumentException::class);
        $this->bundles->fulfill($data);
    }

    public function testWalletShareCannotBeChangedAfterCheckout(): void
    {
        $data = $this->charge($this->bundles->createPending(7, 1, 3000));
        $data['metadata']['app_data']['wallet_used'] = 0;
        $this->expectException(InvalidArgumentException::class);
        $this->bundles->fulfill($data);
    }

    public function testCheckoutRetainsAgreedPriceAfterCatalogChanges(): void
    {
        $data = $this->charge($this->bundles->createPending(7, 1));
        $this->connection->table('plan_bundles')->where('id', 1)->update(['price' => 20000]);
        $this->bundles->fulfill($data);
        $this->assertSame('paid', $this->connection->table('payments')->get()->getRow()->status);
        $this->assertSame(5, (int) $this->connection->table('job_credit_wallets')->get()->getRow()->credits);
    }

    public function testLegacyCheckoutCannotSupplyItsOwnDiscountedPrice(): void
    {
        $data = $this->charge(['reference' => 'legacy-charge', 'amount' => 1,
            'metadata' => ['type' => 'bundle', 'user_id' => 7, 'bundle_id' => 1, 'wallet_used' => 0, 'bundle_price' => 1]]);
        $this->expectException(InvalidArgumentException::class);
        $this->bundles->fulfill($data);
    }

    public function testBundleGrantRetryCannotBeRedirectedToAnotherAccountOrBundle(): void
    {
        $this->bundles->credit(7, 1, 'grant-1', 'wallet');
        foreach ([[8, 1], [7, 2]] as [$userId, $bundleId]) {
            try { $this->bundles->credit($userId, $bundleId, 'grant-1', 'wallet'); $this->fail('Mismatched grant accepted'); }
            catch (InvalidArgumentException $e) { $this->assertSame(1, $this->connection->table('job_credit_wallets')->countAllResults()); }
        }
    }

    public function testInactiveBundleCannotBePurchased(): void
    {
        $this->connection->table('plan_bundles')->where('id', 1)->update(['is_active' => 0]);
        $this->expectException(InvalidArgumentException::class);
        $this->bundles->createPending(7, 1);
    }

    public function testPaidBundleWithoutCreditsCannotReportSuccess(): void
    {
        $order = $this->bundles->createPending(7, 1);
        $this->connection->table('payments')->where('reference', $order['reference'])->update(['status' => 'paid']);
        $this->expectException(RuntimeException::class);
        $this->bundles->fulfill($this->charge($order));
    }

    public function testPaidHybridBundleWithoutWalletDebitRequiresReconciliation(): void
    {
        $order = $this->bundles->createPending(7, 1, 3000);
        $this->connection->table('payments')->where('reference', $order['reference'])->update(['status' => 'paid']);
        $this->bundles->credit(7, 1, $order['reference']);
        $this->expectException(RuntimeException::class);
        $this->bundles->fulfill($this->charge($order));
    }

    public function testFailedPaymentRecordRollsBackWalletAndCredits(): void
    {
        $order = $this->bundles->createPending(7, 1, 3000);
        $this->connection->query("CREATE TRIGGER reject_paid_order BEFORE UPDATE ON payments WHEN NEW.status = 'paid' BEGIN SELECT RAISE(ABORT, 'payment ledger failure'); END");
        try { $this->bundles->fulfill($this->charge($order)); $this->fail('Broken payment record accepted'); }
        catch (\CodeIgniter\Database\Exceptions\DatabaseException | RuntimeException $e) {
            $this->assertSame(5000.0, (float) $this->connection->table('wallets')->get()->getRow()->balance);
            $this->assertSame(0, $this->connection->table('job_credit_wallets')->countAllResults());
            $this->assertSame(0, $this->connection->table('job_credit_transactions')->countAllResults());
            $this->assertSame(0, $this->connection->table('wallet_transactions')->countAllResults());
        }
    }

    public function testLegacyFullPricePaymentWithFlatMetadataWorks(): void
    {
        $data = ['reference' => 'legacy-paid-card', 'status' => 'success', 'currency' => 'NGN', 'amount' => 1000000,
            'customer' => ['email' => 'payer@example.com'], 'metadata' => [
                'type' => 'bundle', 'user_id' => 7, 'bundle_id' => 1, 'wallet_used' => 0]];
        $this->assertFalse($this->bundles->fulfill($data)['already_processed']);
        $this->assertTrue($this->bundles->fulfill($data)['already_processed']);
        $this->assertSame(1, $this->connection->table('payments')->countAllResults());
        $this->assertSame(1, $this->connection->table('job_credit_wallets')->countAllResults());
    }
}
