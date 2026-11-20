<?php
namespace App\Services;

final class CaptchaVerifier
{
    public static function accepts(array $result, string $action, string $hostname): bool
    {
        return ($result['success'] ?? false) === true
            && empty($result['error-codes'])
            && is_numeric($result['score'] ?? null) && (float) $result['score'] >= 0.5 && (float) $result['score'] <= 1.0
            && ($result['action'] ?? '') === $action
            && strtolower((string) ($result['hostname'] ?? '')) === strtolower($hostname);
    }

    public function verify($token, string $action): bool
    {
        $secret = env('recaptcha_secret_key');
        if (!is_string($token) || $token === '' || $token === 'dev-bypass' || !$secret) return false;
        try {
            $response = service('curlrequest')->post('https://www.google.com/recaptcha/api/siteverify', [
                'form_params' => ['secret' => $secret, 'response' => $token], 'timeout' => 8, 'http_errors' => false,
            ]);
            $result = json_decode($response->getBody(), true);
            return $response->getStatusCode() === 200 && is_array($result)
                && self::accepts($result, $action, (string) parse_url(config('App')->baseURL, PHP_URL_HOST));
        } catch (\Throwable $e) {
            log_message('error', 'CAPTCHA verification unavailable.');
            return false;
        }
    }
}
