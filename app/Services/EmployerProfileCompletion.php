<?php

namespace App\Services;

final class EmployerProfileCompletion
{
    public const WEIGHTS = ['identity' => 25, 'contact' => 20, 'about' => 20, 'verify' => 20, 'social' => 15];

    public static function sections(object $employer, array $industryIds): array
    {
        $has = static fn (string $field): bool => trim((string) ($employer->{$field} ?? '')) !== '';

        return [
            'identity' => $has('company_name') && !empty($industryIds) && $has('company_size'),
            'contact' => $has('contact_name') && $has('contact_phone') && $has('contact_email')
                && $has('company_address') && !empty($employer->state_id),
            'about' => $has('description'),
            'verify' => $has('rc_number') || !empty($employer->is_verified),
            'social' => $has('linkedin') || $has('twitter') || $has('facebook') || $has('instagram'),
        ];
    }

    public static function percentage(object $employer, array $industryIds): int
    {
        $total = 0;
        foreach (self::sections($employer, $industryIds) as $section => $complete) {
            if ($complete) {
                $total += self::WEIGHTS[$section];
            }
        }
        return $total;
    }
}
