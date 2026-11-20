<?php
require __DIR__ . '/_support/isolated-bootstrap.php';
$config = config('Database');
$config->tests = ['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true];
$db = Config\Database::connect('tests');
foreach ([
 'CREATE TABLE email_suppressions (email_hash TEXT PRIMARY KEY, email TEXT, reason TEXT, event_id TEXT, created_at TEXT)',
 'CREATE TABLE users (id INTEGER PRIMARY KEY, username TEXT, user_type TEXT, active INTEGER DEFAULT 1, deleted_at TEXT)',
 'CREATE TABLE auth_identities (id INTEGER PRIMARY KEY, user_id INTEGER, type TEXT, secret TEXT)',
 'CREATE TABLE employers (id INTEGER PRIMARY KEY, user_id INTEGER, company_name TEXT, contact_name TEXT, contact_email TEXT, company_email TEXT, contact_phone TEXT, company_address TEXT)',
 'CREATE TABLE plans (id INTEGER PRIMARY KEY, name TEXT, features TEXT)',
 'CREATE TABLE plan_bundles (id INTEGER PRIMARY KEY, name TEXT)',
 'CREATE TABLE user_subscriptions (id INTEGER PRIMARY KEY, user_id INTEGER, plan_id INTEGER, ends_at TEXT)',
 'CREATE TABLE payments (id INTEGER PRIMARY KEY, payment_method TEXT, reference TEXT, paid_at TEXT)',
 'CREATE TABLE job_notifications (id INTEGER PRIMARY KEY AUTOINCREMENT, employer_id INTEGER, job_id INTEGER, application_id INTEGER, type TEXT, title TEXT, message TEXT, action_url TEXT, is_read INTEGER, read_at TEXT, created_at TEXT, updated_at TEXT)',
 'CREATE TABLE queue_jobs (id INTEGER PRIMARY KEY AUTOINCREMENT, queue TEXT, payload TEXT, status TEXT, attempts INTEGER DEFAULT 0, error TEXT, available_at TEXT, created_at TEXT, updated_at TEXT)',
] as $sql) $db->query($sql);
$db->table('users')->insertBatch([['id' => 7, 'username' => 'owner', 'user_type' => 'employer'], ['id' => 8, 'username' => 'admin', 'user_type' => 'admin']]);
$db->table('auth_identities')->insertBatch([['id' => 1, 'user_id' => 7, 'type' => 'email_password', 'secret' => 'owner@example.test'], ['id' => 2, 'user_id' => 8, 'type' => 'email_password', 'secret' => 'admin@example.test']]);
$db->table('employers')->insert(['id' => 44, 'user_id' => 7, 'company_name' => 'Acme', 'contact_email' => '', 'contact_phone' => '08012345678', 'company_address' => 'Lagos']);
$db->table('plans')->insert(['id' => 1, 'name' => 'Employer Pro', 'features' => '{}']);
$db->table('plan_bundles')->insert(['id' => 3, 'name' => 'Ten jobs']);
$db->table('user_subscriptions')->insert(['id' => 9, 'user_id' => 7, 'plan_id' => 1, 'ends_at' => date('Y-m-d H:i:s', strtotime('+30 days'))]);
$db->table('payments')->insert(['id' => 10, 'payment_method' => 'wallet', 'reference' => 'payment_test', 'paid_at' => date('Y-m-d H:i:s')]);
$_ENV['email.admin_email'] = 'admin@example.test';
$mail = new class extends App\Libraries\QueuedEmail {
 public array $sent = [];
 public function send($autoClear = true) {
   $this->sent[] = ['to' => $this->recipients, 'subject' => $this->subject, 'body' => $this->body];
   if ($autoClear) $this->clear();
   return true;
 }
};
Config\Services::injectMock('email', $mail);
function check($ok, $message) { if (!$ok) throw new RuntimeException($message); }
$invoices = new App\Services\InvoiceService();
check($invoices->sendSubscriptionInvoice(7, 9, 10, 18000, 1), 'Subscription receipt failed');
check(count($mail->sent) === 2, 'Subscription must email owner and admin');
check(in_array('owner@example.test', $mail->sent[0]['to'], true), 'Receipt must fall back to login email when company contact is blank');
check(str_contains($mail->sent[0]['body'], 'Employer Pro'), 'Receipt is missing package');
check(in_array('admin@example.test', $mail->sent[1]['to'], true), 'Admin payment notice missing');
check($invoices->sendBundleInvoice(7, 3, 10, 10000, 10), 'Bundle receipt failed');
$employer = model(App\Models\EmployerModel::class)->find(44);
$user = model(CodeIgniter\Shield\Models\UserModel::class)->find(7);
$notifications = new App\Services\EmailNotificationService();
check($notifications->sendEmployerVerificationApprovedEmail($employer, $user), 'Approval email failed');
check(count($mail->sent) === 6, 'Verification must email employer and admin');
check($db->table('job_notifications')->countAllResults() === 1, 'Verification in-app notice missing');
check($notifications->sendEmployerAccountEvent($employer, $user, 'Wallet funded', 'Your wallet was funded.', ['Amount' => '1000.00']), 'Account email failed');
check(count($mail->sent) === 8, 'Manual change must email employer and admin');
check($notifications->sendEmployerVerificationApprovedEmail($employer, $user, '', false), 'Verification resend failed');
check(count($mail->sent) === 10 && $db->table('job_notifications')->countAllResults() === 1, 'Resend must email both recipients without duplicating in-app notices');
// Exercise actual delivery failure without contacting a mail server.
$_ENV['email_use_queue'] = false;
$failed = new class extends App\Libraries\QueuedEmail {
 public int $apiAttempts = 0;
 protected function sendDirect(): bool { throw new RuntimeException('SMTP unavailable'); }
 protected function sendViaApi(): bool { $this->apiAttempts++; return false; }
};
$failed->setFrom('info@example.test', 'JobberRecruit')->setTo('owner@example.test')->setSubject('Retry test')->setMessage('<p>Keep me</p>');
check($failed->send(), 'Failed mail should be retained for retry');
check($failed->apiAttempts === 1, 'SMTP exception must still attempt API failover');
check($db->table('queue_jobs')->countAllResults() === 1, 'Failed message missing from retry queue');
$payload = json_decode($db->table('queue_jobs')->get()->getRow()->payload, true);
check($payload['data']['from_email'] === 'info@example.test', 'Retry queue lost sender');
check($payload['data']['subject'] === 'Retry test' && trim($payload['data']['message']) === '<p>Keep me</p>', 'Retry queue lost original message');
Config\Services::$bypassQueue = true;
$failed->setFrom('info@example.test')->setTo('owner@example.test')->setSubject('Worker test')->setMessage('Retry');
check(!$failed->send(), 'Worker must report failed delivery');
check($db->table('queue_jobs')->countAllResults() === 1, 'Worker must not recursively queue failed mail');
Config\Services::$bypassQueue = false;
$db->close();
echo "PASS: subscription array records, owner/admin receipts, verification, manual events, SMTP exception failover, durable retry, and no recursive queueing. No external emails sent.\n";