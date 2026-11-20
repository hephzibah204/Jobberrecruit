<?php
require __DIR__ . '/_support/isolated-bootstrap.php';
set_exception_handler(static function (Throwable $e) { fwrite(STDERR, $e->getMessage() . PHP_EOL . $e->getTraceAsString()); exit(1); });
function check($ok, $message) { if (!$ok) throw new RuntimeException($message); }
$config = config('Database');
$config->tests = ['DBDriver' => 'SQLite3', 'database' => ':memory:', 'DBPrefix' => '', 'DBDebug' => true];
$db = Config\Database::connect('tests');
require APPPATH . 'Database/Migrations/2026-10-28-120000_CreateEmailSuppressions.php';
(new App\Database\Migrations\CreateEmailSuppressions(Config\Database::forge('tests')))->up();
$db->query('CREATE TABLE queue_jobs (id INTEGER PRIMARY KEY AUTOINCREMENT, queue TEXT, payload TEXT, status TEXT, attempts INTEGER DEFAULT 0, error TEXT, available_at TEXT, claim_token TEXT, reserved_until TEXT, created_at TEXT, updated_at TEXT)');
$db->query('CREATE TABLE newsletter_subscribers (id INTEGER PRIMARY KEY AUTOINCREMENT, email TEXT, is_active INTEGER)');
$db->table('newsletter_subscribers')->insert(['email' => 'Opted-Out@Example.Test', 'is_active' => 1]);
$suppression = new App\Services\EmailSuppressionService($db);
$suppression->record(['event' => 'soft bounce', 'email' => 'soft@example.test']);
check(!$suppression->isSuppressed('soft@example.test'), 'Temporary bounce must not suppress');
$suppression->record(['event' => 'bounce', 'email' => 'Hard@Example.Test ', 'event_id' => 'evt1']);
$suppression->record(['event' => 'bounce', 'email' => 'hard@example.test', 'event_id' => 'evt1']);
check($suppression->isSuppressed(' HARD@example.test') && $db->table('email_suppressions')->countAllResults() === 1, 'Hard bounce suppression must normalize and deduplicate');
$suppression->record(['event' => 'unsubscribe', 'email' => 'opted-out@example.test', 'event_id' => 'evt2']);
check($suppression->isSuppressed('opted-out@example.test') && $db->table('email_suppressions')->countAllResults() === 2, 'Unsubscribed recipients must be suppressed too');
check((int) $db->table('newsletter_subscribers')->where('email', 'Opted-Out@Example.Test')->get()->getRow()->is_active === 0, 'Unsubscribed newsletter subscribers must be deactivated locally');
$body = '{"events":[]}'; $secret = 'test-webhook-secret';
check(App\Services\EmailSuppressionService::authentic($body, hash_hmac('sha256', $body, $secret), $secret), 'Valid webhook signature rejected');
check(!App\Services\EmailSuppressionService::authentic($body . ' ', hash_hmac('sha256', $body, $secret), $secret), 'Tampered webhook accepted');
check(!App\Services\EmailSuppressionService::authentic($body, '', ''), 'Unconfigured webhook accepted');
$valid = ['success' => true, 'score' => 0.9, 'action' => 'register', 'hostname' => 'jobberrecruit.com'];
check(App\Services\CaptchaVerifier::accepts($valid, 'register', 'jobberrecruit.com'), 'Valid CAPTCHA rejected');
foreach ([['score' => 0.1], ['action' => 'login'], ['hostname' => 'attacker.test'], ['success' => false], ['error-codes' => ['Over free quota.']]] as $bad) {
 check(!App\Services\CaptchaVerifier::accepts(array_merge($valid, $bad), 'register', 'jobberrecruit.com'), 'Invalid CAPTCHA accepted');
}
check(!(new App\Services\CaptchaVerifier())->verify('dev-bypass', 'register'), 'CAPTCHA development bypass accepted');
$throttler = new CodeIgniter\Throttle\Throttler(new CodeIgniter\Test\Mock\MockCache());
Config\Services::injectMock('throttler', $throttler);
$guard = new App\Services\AuthAbuseGuard();
check($guard->allow('register', '192.0.2.1', 'same@example.test'), 'First signup blocked');
check($guard->allow('register', '192.0.2.2', 'same@example.test'), 'Second signup blocked');
check(!$guard->allow('register', '192.0.2.3', ' SAME@example.test '), 'Per-address registration limit bypassed by IP/case change');
for ($i=0; $i<5; $i++) check($guard->allow('verification', '192.0.2.4', '7'), 'Normal verification request blocked');
check(!$guard->allow('verification', '192.0.2.5', '7'), 'Per-account resend limit bypassed by IP change');
$mail = new class extends App\Libraries\QueuedEmail {
 public int $attempts = 0;
 protected function sendDirect(): bool { $this->attempts++; return true; }
};
$mail->setFrom('sender@example.test')->setTo(['hard@example.test', 'ok@example.test'])->setCC(['hard@example.test'])->setBCC(['hard@example.test'])->setSubject('Test')->setMessage('test');
check($mail->send() && $mail->attempts === 1, 'Valid recipient should remain deliverable');
$mail->setFrom('sender@example.test')->setTo('hard@example.test')->setSubject('Test')->setMessage('test');
try { $mail->send(); throw new RuntimeException('Suppressed mail was sent'); } catch (App\Services\PermanentEmailFailure $e) {}
check($mail->attempts === 1, 'Suppressed recipient reached transport');
$invalid = new class extends App\Libraries\QueuedEmail {
 public bool $apiCalled = false;
 protected function sendDirect(): bool { return false; }
 protected function sendViaApi(): bool { $this->apiCalled = true; return true; }
 public function printDebugger($include = ['headers', 'subject', 'body']) { return '550 5.1.1 No such mailbox'; }
};
$invalid->setFrom('sender@example.test')->setTo('missing@example.test')->setSubject('test')->setMessage('test');
try { $invalid->send(); throw new RuntimeException('Permanent SMTP rejection accepted'); } catch (App\Services\PermanentEmailFailure $e) {}
check(!$invalid->apiCalled && $suppression->isSuppressed('missing@example.test'), 'Invalid SMTP recipient must be suppressed without API retry');
$queue = new App\Models\JobQueueModel($db);
$permanentId = $queue->dispatch('transactional_email', ['to' => 'hard@example.test']);
$worker = new class($queue) extends App\Services\EmailQueueWorker {
 protected function deliver(string $type, array $data): bool { throw new App\Services\PermanentEmailFailure('Recipient suppressed'); }
};
$worker->process(1);
check($queue->find($permanentId)->status === 'failed' && (int) $queue->find($permanentId)->attempts === 1, 'Permanent failure must stop after one attempt');
$temporaryId = $queue->dispatch('transactional_email', []);
$worker = new class($queue) extends App\Services\EmailQueueWorker { protected function deliver(string $type, array $data): bool { return false; } };
$worker->process(1);
check($queue->find($temporaryId)->status === 'pending', 'Temporary failure should be retried');
check(App\Services\MailtrapService::isPermanentResponse(422) && !App\Services\MailtrapService::isPermanentResponse(429) && !App\Services\MailtrapService::isPermanentResponse(503), 'Provider failure classification incorrect');
$db->close();
echo "PASS: migration, CAPTCHA action/hostname/score, signup/resend limits, signed webhooks, normalized hard-bounce suppression, transport blocking, and permanent versus temporary retries. No external emails sent.\n";
