<?php

use App\Services\EmployerProfileCompletion;
use PHPUnit\Framework\TestCase;

final class EmployerProfileCompletionTest extends TestCase
{
    public function testSocialLinksAreRequiredForFinalFifteenPercent(): void
    {
        $employer = (object) [
            'company_name' => 'Example Company', 'company_size' => '10-50',
            'contact_name' => 'Owner', 'contact_phone' => '08012345678',
            'contact_email' => 'owner@example.com', 'company_address' => 'Lagos',
            'state_id' => 1, 'description' => 'Company description', 'rc_number' => 'RC123',
        ];
        self::assertSame(85, EmployerProfileCompletion::percentage($employer, [1]));
        foreach (['linkedin', 'twitter', 'facebook', 'instagram'] as $field) {
            $employer->{$field} = 'https://example.com/company';
            self::assertSame(100, EmployerProfileCompletion::percentage($employer, [1]));
            $employer->{$field} = '   ';
            self::assertSame(85, EmployerProfileCompletion::percentage($employer, [1]));
            self::assertFalse(EmployerProfileCompletion::sections($employer, [1])['social']);
        }
        self::assertSame(60, EmployerProfileCompletion::percentage($employer, []));
    }

    public function testEmptyProfileAndSocialOnlyProfile(): void
    {
        $employer = new stdClass();
        self::assertSame(0, EmployerProfileCompletion::percentage($employer, []));
        $employer->linkedin = 'https://linkedin.com/company/example';
        $employer->facebook = 'https://facebook.com/example';
        self::assertSame(15, EmployerProfileCompletion::percentage($employer, []));
    }
}
