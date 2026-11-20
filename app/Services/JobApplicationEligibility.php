<?php

namespace App\Services;

class JobApplicationEligibility
{
    public static function method($job): string
    {
        $method = is_array($job) ? ($job['application_method'] ?? '') : ($job->application_method ?? '');
        $method = strtolower(trim((string) $method));
        return in_array($method, ['external', 'whatsapp', 'email'], true) ? $method : 'form';
    }
    public static function accepts($job, ?int $now = null): bool
    {
        $read = static fn ($key) => is_array($job) ? ($job[$key] ?? null) : ($job->{$key} ?? null);
        if (strtolower((string) $read('status')) !== 'open' || strtolower((string) $read('admin_status')) !== 'approved') {
            return false;
        }
        $today = date('Y-m-d', $now ?? time());
        foreach (['application_deadline', 'expiry_date'] as $field) {
            $value = $read($field);
            if ($value && substr((string) $value, 0, 10) !== '0000-00-00' && substr((string) $value, 0, 10) < $today) {
                return false;
            }
        }
        return true;
    }
}
