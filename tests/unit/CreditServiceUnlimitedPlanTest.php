<?php

use App\Services\CreditService;
use CodeIgniter\Test\CIUnitTestCase;

final class CreditServiceUnlimitedPlanTest extends CIUnitTestCase
{
    private function service(): CreditService
    {
        return (new ReflectionClass(CreditService::class))->newInstanceWithoutConstructor();
    }

    public function testExplicitUnlimitedPostingFeatureGrantsAccess(): void
    {
        $plan = (object) [
            'plan_type' => 'employer',
            'name' => 'Enterprise',
            'code' => 'enterprise_monthly',
            'features' => ['unlimited_job_postings' => true],
        ];

        $this->assertTrue($this->service()->planProvidesUnlimitedPosting($plan));
    }

    public function testLegacyUnlimitedEmployerPlanNameGrantsAccess(): void
    {
        $plan = (object) [
            'plan_type' => 'employer',
            'name' => 'Unlimited Job Posting',
            'code' => 'employer_unlimited',
            'features' => '{}',
        ];

        $this->assertTrue($this->service()->planProvidesUnlimitedPosting($plan));
    }

    public function testCandidatePlanNamedUnlimitedDoesNotGrantEmployerPosting(): void
    {
        $plan = (object) [
            'plan_type' => 'candidate',
            'name' => 'Unlimited AI Access',
            'code' => 'candidate_unlimited',
            'features' => ['unlimited' => true],
        ];

        $this->assertFalse($this->service()->planProvidesUnlimitedPosting($plan));
    }

    public function testOrdinaryCreditPlanIsNotUnlimited(): void
    {
        $plan = [
            'plan_type' => 'employer',
            'name' => 'Business Monthly',
            'code' => 'business_monthly',
            'features' => ['featured' => true],
        ];

        $this->assertFalse($this->service()->planProvidesUnlimitedPosting($plan));
    }
}
