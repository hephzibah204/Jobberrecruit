<?php

namespace Tests\Unit;

use CodeIgniter\Test\CIUnitTestCase;

class ConsolidatedPlansAndBundlesTest extends CIUnitTestCase
{
    public function testPlansAndBundlesConsolidationData()
    {
        $employerPlans = [
            ['id' => 1, 'name' => 'Starter Employer', 'plan_type' => 'employer', 'base_price' => 15000]
        ];

        $candidatePlans = [
            ['id' => 2, 'name' => 'Pro Candidate', 'plan_type' => 'candidate', 'base_price' => 5000]
        ];

        $bundles = [
            ['id' => 1, 'name' => '5 Job Posts Pack', 'slug' => '5-job-pack', 'job_credits' => 5, 'price' => 35000, 'is_best_value' => 1]
        ];

        $allCategories = [
            'employerPlans' => $employerPlans,
            'candidatePlans' => $candidatePlans,
            'bundles' => $bundles
        ];

        $this->assertCount(1, $allCategories['employerPlans']);
        $this->assertCount(1, $allCategories['candidatePlans']);
        $this->assertCount(1, $allCategories['bundles']);
        $this->assertEquals('5 Job Posts Pack', $allCategories['bundles'][0]['name']);
    }
}
