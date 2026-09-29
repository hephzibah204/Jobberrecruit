<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class ConfigureEmployerCandidateSearchPlans extends Migration
{
    public function up()
    {
        $plans = [
            [
                'code'                => 'monthly_search',
                'name'                => 'Monthly Search Plan',
                'base_price'          => 150000.00,
                'billing_type'        => 'subscription',
                'plan_type'           => 'employer',
                'monthly_job_credits' => 10,
                'features'            => json_encode([
                    'candidate_search_credits' => 10,
                    'duration_months'          => 1,
                    'duration_label'           => '1 Month',
                    'candidate_messaging'      => true,
                    'priority_support'         => true,
                ]),
                'is_active'           => 1,
            ],
            [
                'code'                => 'quarterly_search',
                'name'                => 'Quarterly Search Plan',
                'base_price'          => 250000.00,
                'billing_type'        => 'subscription',
                'plan_type'           => 'employer',
                'monthly_job_credits' => 35,
                'features'            => json_encode([
                    'candidate_search_credits' => 35,
                    'duration_months'          => 3,
                    'duration_label'           => '3 Months',
                    'candidate_messaging'      => true,
                    'priority_support'         => true,
                ]),
                'is_active'           => 1,
            ],
            [
                'code'                => 'semi_annual_search',
                'name'                => 'Semi-Annual Search Plan',
                'base_price'          => 450000.00,
                'billing_type'        => 'subscription',
                'plan_type'           => 'employer',
                'monthly_job_credits' => 80,
                'features'            => json_encode([
                    'candidate_search_credits' => 80,
                    'duration_months'          => 6,
                    'duration_label'           => '6 Months',
                    'candidate_messaging'      => true,
                    'priority_support'         => true,
                ]),
                'is_active'           => 1,
            ],
            [
                'code'                => 'annual_search',
                'name'                => 'Annual Search Plan',
                'base_price'          => 750000.00,
                'billing_type'        => 'subscription',
                'plan_type'           => 'employer',
                'monthly_job_credits' => 180,
                'features'            => json_encode([
                    'candidate_search_credits' => 180,
                    'duration_months'          => 12,
                    'duration_label'           => '1 Year',
                    'candidate_messaging'      => true,
                    'priority_support'         => true,
                ]),
                'is_active'           => 1,
            ],
        ];

        $now = date('Y-m-d H:i:s');
        foreach ($plans as $p) {
            $existing = $this->db->table('plans')->where('code', $p['code'])->get()->getRow();
            if ($existing) {
                $this->db->table('plans')->where('id', $existing->id)->update(array_merge($p, ['updated_at' => $now]));
            } else {
                $this->db->table('plans')->insert(array_merge($p, [
                    'created_at' => $now,
                    'updated_at' => $now,
                ]));
            }
        }
    }

    public function down()
    {
        $this->db->table('plans')->whereIn('code', [
            'monthly_search',
            'quarterly_search',
            'semi_annual_search',
            'annual_search',
        ])->delete();
    }
}
