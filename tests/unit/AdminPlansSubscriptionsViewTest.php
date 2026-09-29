<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

class AdminPlansSubscriptionsViewTest extends CIUnitTestCase
{
    public function testSubscriptionArrayOrObjectCasting()
    {
        $subscriptions = [
            [
                'id' => 1,
                'user_id' => 10,
                'plan_id' => 2,
                'plan_name' => 'Pro Employer',
                'plan_type' => 'employer',
                'company_name' => 'Acme Corp',
                'candidate_name' => null,
                'ends_at' => '2026-12-31 23:59:59',
                'is_active' => 1
            ],
            [
                'id' => 2,
                'user_id' => 11,
                'plan_id' => 3,
                'plan_name' => 'Candidate Pro',
                'plan_type' => 'candidate',
                'company_name' => null,
                'candidate_name' => 'John Doe',
                'ends_at' => '2026-10-15 23:59:59',
                'is_active' => 1
            ]
        ];

        foreach ($subscriptions as $sub) {
            $subObj = (object) $sub;

            $this->assertNotEmpty($subObj->plan_type);
            if ($subObj->plan_type === 'employer') {
                $this->assertEquals('Acme Corp', $subObj->company_name);
            } else {
                $this->assertEquals('John Doe', $subObj->candidate_name);
            }
        }
    }
}
