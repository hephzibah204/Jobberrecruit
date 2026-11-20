<?php
namespace CodeIgniter { class Model {
    public array $updates = [];
    public bool $succeeds = true;
    public function find($id) { return $id === 7 ? (object) ['id' => 7] : null; }
    public function update($id, $data) { $this->updates[] = $data; return $this->succeeds; }
} }
namespace {
require dirname(__DIR__) . '/app/Models/EmployerModel.php';
require dirname(__DIR__) . '/app/Models/JobNotificationModel.php';
require dirname(__DIR__) . '/app/Services/EmailNotificationService.php';
$notices = new class {
    public array $calls = [];
    public bool $throws = false;
    public function insert($data) { return true; }
    public function createNotification(...$args) {
        $this->calls[] = $args;
        if ($this->throws) throw new RuntimeException('notification storage unavailable');
        return true;
    }
};
function model($class) { return $GLOBALS['notices']; }
function base_url($path) { return 'https://example.test/' . $path; }
function log_message(...$args) {}
function check($ok, $message) { if (!$ok) throw new RuntimeException($message); }
$model = new App\Models\EmployerModel();
check($model->verifyEmployer(7, 1), 'Approval failed');
check($model->updates[0]['is_verified'] === 1 && $model->updates[0]['verification_status'] === 'verified', 'Badge and status must be saved together');
$model->succeeds = false;
check(!$model->verifyEmployer(7, 1), 'Failed writes must not report success');
check(!$model->verifyEmployer(99, 1), 'Missing employer must not report success');
$mailer = new class extends App\Services\EmailNotificationService {
    public array $sent = [];
    public bool $result = true;
    public function __construct() {}
    protected function sendEmail(string $to, string $subject, string $view, array $data): bool {
        $this->sent[] = [$to, $view]; return $this->result;
    }
};
$employer = (object) ['id' => 7, 'company_name' => 'Acme', 'contact_email' => 'contact@example.test'];
check($mailer->sendEmployerVerificationApprovedEmail($employer, (object) ['email' => 'owner@example.test']), 'Email failed');
check($mailer->sent[0] === ['owner@example.test', 'emails/verification_approved'], 'Approval email recipient/template incorrect');
check($notices->calls[0][0] === 7 && $notices->calls[0][6] === base_url('employer/profile'), 'Notice must target employer and link to profile');
$mailer->result = false;
check(!$mailer->sendEmployerVerificationApprovedEmail($employer, null), 'Mail failure must be returned');
check(count($notices->calls) === 2, 'Mail failure must preserve in-app notification');
check($mailer->sent[1][0] === 'contact@example.test', 'Contact email fallback missing');
$notices->throws = true;
$mailer->result = true;
check($mailer->sendEmployerVerificationApprovedEmail($employer, null), 'Notification failure must not block email');
echo "PASS: verification badge persistence, failed writes, approval email, employer notification, contact fallback, and independent delivery failures.\n";
}