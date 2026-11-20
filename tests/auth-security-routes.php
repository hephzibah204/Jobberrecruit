<?php
require __DIR__ . '/_support/isolated-bootstrap.php';
set_exception_handler(static function (Throwable $e) { fwrite(STDERR, $e->getMessage()); exit(1); });
$routes = service('routes');
$routes->loadRoutes();
$post = $routes->getRoutes('POST');
$get = $routes->getRoutes('GET');
foreach (['register' => 'AuthController::register', 'auth/resend-verification' => 'AuthController::resendVerification', 'webhooks/mailtrap' => 'MailtrapWebhookController::receive'] as $path => $handler) {
    if (!isset($post[$path]) || !str_contains($post[$path], $handler)) throw new RuntimeException('Wrong handler for ' . $path);
}
if (isset($get['auth/resend-verification'])) throw new RuntimeException('GET resend must not remain exposed');
$source = file_get_contents(APPPATH . 'Controllers/AuthController.php');
$registration = substr($source, strpos($source, 'public function register()'), strpos($source, 'private function assignFreePlanToUser') - strpos($source, 'public function register()'));
if (str_contains($registration, 'auth->attempt') || str_contains($registration, "ENVIRONMENT === 'development'")) throw new RuntimeException('Registration must not sign in unverified users through development settings');
$resendStart = strpos($source, 'public function resendVerification()');
$resendEnd = strpos($source, 'public function forgotPassword()', $resendStart);
$resend = substr($source, $resendStart, $resendEnd - $resendStart);
if (!str_contains($resend, "'pending_email_verification'") || !str_contains($resend, "'resend_verification'")) throw new RuntimeException('Resend must validate CAPTCHA and accept only a session-bound pending account');
if (!str_contains($registration, "'website_url'") || !str_contains($registration, "'register'")) throw new RuntimeException('Registration must enforce honeypot and server-side CAPTCHA checks');
if (!str_contains($source, 'if (empty($user->email_verified_at))')) throw new RuntimeException('Unverified accounts must not sign in in development');
$mailtrap = file_get_contents(APPPATH . 'Services/MailtrapService.php');
$emailConfig = file_get_contents(APPPATH . 'Config/Email.php');
if (preg_match('/\?:\s*[\'\"][a-f0-9]{32,}[\'\"]/', $mailtrap . $emailConfig)) throw new RuntimeException('Mailtrap API credentials must not be embedded in source');
if (!str_contains($mailtrap, "['cc', 'bcc']")) throw new RuntimeException('Mailtrap API fallback must preserve CC and BCC recipients');
$verifyStart = strpos($source, 'public function verifyEmailToken(');
$verifyEnd = strpos($source, 'public function resendVerification()', $verifyStart);
$verify = substr($source, $verifyStart, $verifyEnd - $verifyStart);
if (!str_contains($verify, 'transBegin()') || !str_contains($verify, "where('token', \$token)") || !str_contains($verify, 'affectedRows() !== 1') || !str_contains($verify, '!$updated')) throw new RuntimeException('Verification tokens must be consumed once and checked against a committed account update');
$resetStart = strpos($source, 'public function resetPassword(');
$resetEnd = strpos($source, 'protected function getUserProvider()', $resetStart);
$reset = substr($source, $resetStart, $resetEnd - $resetStart);
if (!str_contains($reset, 'transBegin()') || !str_contains($reset, 'affectedRows() !== 1') || !str_contains($reset, '!$users->save($user)')) throw new RuntimeException('Password reset must consume its token only when the password update commits');
$forgotStart = strpos($source, 'public function forgotPassword()');
$forgotEnd = strpos($source, 'public function resetPassword(', $forgotStart);
$forgot = substr($source, $forgotStart, $forgotEnd - $forgotStart);
if (!str_contains($forgot, "'password_reset'") || substr_count($forgot, '$genericResetMessage') < 4) throw new RuntimeException('Password reset requests must be throttled and use a non-enumerating response');
echo "PASS: actual signup/resend/webhook routes use protected handlers, no GET resend, and registration does not auto-login.\n";
