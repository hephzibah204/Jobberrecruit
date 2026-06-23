<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class SubscriptionPlanSeeder extends Seeder
{
    public function run()
    {
        $data = [
            [
                'name'           => 'Free Plan',
                'slug'           => 'free',
                'price'          => 0,
                'duration'       => 3650, // ~10 years
                'job_limit'      => 1,
                'featured_limit' => 0,
                'description'    => 'Basic access with 1 active job posting.'
            ],
            [
                'name'           => 'Standard Plan',
                'slug'           => 'standard',
                'price'          => 10000,
                'duration'       => 30,
                'job_limit'      => 10,
                'featured_limit' => 1,
                'description'    => 'Ideal for SMEs with moderate hiring needs.'
            ],
            [
                'name'           => 'Pro Plan',
                'slug'           => 'pro',
                'price'          => 30000,
                'duration'       => 30,
                'job_limit'      => 50,
                'featured_limit' => 5,
                'description'    => 'Advanced hiring tools and extended access.'
            ],
            [
                'name'           => 'Enterprise Plan',
                'slug'           => 'enterprise',
                'price'          => 0, // custom pricing
                'duration'       => 30,
                'job_limit'      => -1,
                'featured_limit' => -1,
                'description'    => 'Custom solutions for large organizations.'
            ]
        ];

        $this->db->table('subscription_plans')->insertBatch($data);
    }
}
