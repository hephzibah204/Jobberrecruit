<?php
require __DIR__ . '/_support/isolated-bootstrap.php';
$db = Config\Database::connect(['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true, 'foreignKeys' => true], false);
foreach ([
 'CREATE TABLE users (id INTEGER PRIMARY KEY)',
 'CREATE TABLE employers (id INTEGER PRIMARY KEY, user_id INTEGER)',
 'CREATE TABLE plans (id INTEGER PRIMARY KEY, name TEXT, is_active INTEGER, plan_type TEXT, monthly_job_credits INTEGER)',
 'CREATE TABLE plan_bundles (id INTEGER PRIMARY KEY, name TEXT, is_active INTEGER, job_credits INTEGER)',
 'CREATE TABLE user_subscriptions (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, plan_id INTEGER, starts_at TEXT, ends_at TEXT, is_active INTEGER, auto_renew INTEGER, created_at TEXT, updated_at TEXT)',
 'CREATE TABLE job_credit_wallets (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, credits INTEGER, source TEXT, reference_id INTEGER, expires_at TEXT, created_at TEXT, updated_at TEXT)',
 'CREATE TABLE job_credit_transactions (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER, type TEXT, credits INTEGER, reference TEXT UNIQUE, description TEXT, meta TEXT, created_at TEXT, updated_at TEXT)',
 'CREATE TABLE wallets (id INTEGER PRIMARY KEY AUTOINCREMENT, user_id INTEGER UNIQUE, balance REAL DEFAULT 0, is_locked INTEGER DEFAULT 0, created_at TEXT, updated_at TEXT)',
 'CREATE TABLE wallet_transactions (id INTEGER PRIMARY KEY AUTOINCREMENT, wallet_id INTEGER, type TEXT, amount REAL, reference TEXT UNIQUE, description TEXT)',
] as $sql) $db->query($sql);
$db->table('users')->insertBatch([['id' => 7], ['id' => 8]]);
$db->table('employers')->insertBatch([['id' => 44, 'user_id' => 7], ['id' => 45, 'user_id' => 8]]);
$db->table('plans')->insertBatch([
 ['id' => 1, 'name' => 'Employer Pro', 'is_active' => 1, 'plan_type' => 'subscription', 'monthly_job_credits' => 5],
 ['id' => 2, 'name' => 'Candidate', 'is_active' => 1, 'plan_type' => 'candidate', 'monthly_job_credits' => 0],
 ['id' => 3, 'name' => 'Inactive', 'is_active' => 0, 'plan_type' => 'subscription', 'monthly_job_credits' => 1],
]);
$db->table('plan_bundles')->insert(['id' => 3, 'name' => 'Ten jobs', 'is_active' => 1, 'job_credits' => 10]);
$notices = []; $service = new App\Services\AdminEmployerAccountService($db, function ($employer, $event) use (&$notices) { $notices[] = $event; return true; });
function check($ok, $message) { if (!$ok) throw new RuntimeException($message); }
function input($action, $extra = []) { return array_merge(['action' => $action, 'reason' => 'Offline account adjustment', 'request_key' => bin2hex(random_bytes(16))], $extra); }
function balance($db) {
 return (int) $db->table('job_credit_wallets')->selectSum('credits', 'total')->where('user_id', 7)->groupStart()->where('expires_at', null)->orWhere('expires_at >', date('Y-m-d H:i:s'))->groupEnd()->get()->getRow()->total;
}
function rejected(callable $operation) {
 try { $operation(); } catch (InvalidArgumentException $e) { return; }
 throw new RuntimeException('Invalid request was accepted');
}
$grant = input('bundle', ['item_id' => '3']);
$service->apply(44, 1, $grant);
$service->apply(44, 1, $grant);
check(balance($db) === 10, 'Bundle replay granted credits twice');
check(count($notices) === 1, 'Bundle replay sent duplicate notices');
rejected(fn () => $service->apply(44, 1, array_merge($grant, ['reason' => 'Changed payload'])));
$service->apply(44, 1, input('subscription', ['item_id' => '1', 'ends_on' => date('Y-m-d', strtotime('+30 days'))]));
check(balance($db) === 15, 'Subscription must keep bundle credits and grant plan credits');
$service->apply(44, 1, input('subscription', ['item_id' => '1', 'ends_on' => date('Y-m-d', strtotime('+60 days'))]));
check(balance($db) === 15, 'Replacing subscription must replace unused subscription credits');
check($db->table('user_subscriptions')->where('is_active', 1)->countAllResults() === 1, 'Multiple active subscriptions');
$service->apply(44, 1, input('credits', ['credits' => '2']));
check(balance($db) === 2, 'Credit reduction failed');
$service->apply(44, 1, input('credits', ['credits' => '20']));
check(balance($db) === 20, 'Credit increase failed');
$service->apply(44, 1, input('credits', ['credits' => '0']));
check(balance($db) === 0, 'Setting zero credits failed');
$fund = input('fund', ['amount' => '12500.50']);
$service->apply(44, 1, $fund);
$service->apply(44, 1, $fund);
check((float) $db->table('wallets')->where('user_id', 7)->get()->getRow()->balance === 12500.5, 'Wallet funding/replay incorrect');
check($db->table('wallet_transactions')->countAllResults() === 1, 'Funding replay created duplicate ledger entries');
check($db->table('job_credit_wallets')->where('user_id', 8)->countAllResults() === 0, 'Changed another employer');
foreach ([
 input('credits', ['credits' => '-1']), input('credits', ['credits' => '1.5']), input('credits', ['credits' => []]),
 input('fund', ['amount' => '-1']), input('fund', ['amount' => '1.001']), input('fund', ['amount' => 'INF']),
 input('subscription', ['item_id' => '2', 'ends_on' => date('Y-m-d')]),
 input('subscription', ['item_id' => '3', 'ends_on' => date('Y-m-d')]),
 input('subscription', ['item_id' => '1', 'ends_on' => '2026-02-30']), input('bundle', ['item_id' => 99]),
 input('fund', ['amount' => '1', 'reason' => '']),
] as $bad) rejected(fn () => $service->apply(44, 1, $bad));
rejected(fn () => $service->apply(999, 1, input('fund', ['amount' => '1'])));
// Force a journal failure after funding. Both the wallet and its ledger must roll back.
$db->query("CREATE TRIGGER reject_admin_journal BEFORE INSERT ON job_credit_transactions BEGIN SELECT RAISE(ABORT, 'test journal failure'); END");
$failed = false;
try { $service->apply(44, 1, input('fund', ['amount' => '100'])); } catch (Throwable $e) { $failed = true; }
check($failed, 'Journal failure did not fail the operation');
check((float) $db->table('wallets')->where('user_id', 7)->get()->getRow()->balance === 12500.5, 'Partial wallet change survived rollback');
check($db->table('wallet_transactions')->countAllResults() === 1, 'Partial wallet ledger survived rollback');
$db->close();
echo "PASS: subscriptions, bundles, credit increases/reductions/zero, funding, audit, replay protection, validation, isolation, and transactional rollback.\n";