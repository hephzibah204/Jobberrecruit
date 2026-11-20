<?php

use App\Services\JobApplicationEligibility;
use CodeIgniter\Test\CIUnitTestCase;

final class JobApplicationEligibilityTest extends CIUnitTestCase
{
    public function testLegacyInternalMethodsCannotBounceBetweenApplicationHandlers(): void
    {
        foreach ([null, '', 'form', 'internal', 'internal_form', 'Application Form', 'unknown'] as $method) {
            $this->assertSame('form', JobApplicationEligibility::method((object) ['application_method' => $method]));
        }
        $this->assertSame('external', JobApplicationEligibility::method(['application_method' => ' EXTERNAL ']));
    }
    public function testOnlyApprovedOpenUnexpiredJobsAcceptApplications(): void
    {
        $job = ['status' => 'open', 'admin_status' => 'approved', 'application_deadline' => '2026-10-16'];
        $now = strtotime('2026-10-16 12:00:00');
        $this->assertTrue(JobApplicationEligibility::accepts($job, $now));
        foreach ([['admin_status' => 'pending'], ['status' => 'paused'], ['status' => 'draft'],
            ['application_deadline' => '2026-10-15'], ['expiry_date' => '2026-10-15']] as $change) {
            $this->assertFalse(JobApplicationEligibility::accepts(array_replace($job, $change), $now));
        }
    }
}
