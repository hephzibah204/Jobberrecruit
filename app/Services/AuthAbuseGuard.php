<?php
namespace App\Services;

final class AuthAbuseGuard
{
    public function allow(string $action, string $ip, string $identity): bool
    {
        $limits = $action === 'register' ? [[5, 3600], [2, 86400]] : [[20, 3600], [5, 3600]];
        $throttler = service('throttler');
        foreach ([$ip, strtolower(trim($identity))] as $index => $value) {
            if (!$throttler->check('auth_' . hash('sha256', $action . ':' . $index . ':' . $value), $limits[$index][0], $limits[$index][1])) return false;
        }
        return true;
    }
}
