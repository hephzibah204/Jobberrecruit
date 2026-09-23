<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class SeedEmployerPlansAndBundles extends Migration
{
    public function up()
    {
        // 1. Insert the employer subscription plan if not already present
        $existing = $this->db->table('plans')
            ->where('plan_type', 'subscription')
            ->get()
            ->getRow();

        if (! $existing) {
            $pricingTiers = json_encode([
                '1'  => 18000,
                '3'  => 48000,
                '6'  => 84000,
                '12' => 150000,
            ]);
            $features = json_encode([
                'unlimited_jobs'   => true,
                'featured_job'     => true,
                'network_blast'    => true,
                'anonymous_job'    => true,
                'url_redirect'     => true,
                'verified_badge'   => true,
                'priority_support' => true,
                'advanced_search'  => true,
            ]);
            $this->db->table('plans')->insert([
                'code'                => 'business_pro',
                'name'                => 'Business Pro',
                'base_price'          => 18000,
                'pricing_tiers'       => $pricingTiers,
                'billing_type'        => 'recurring',
                'plan_type'           => 'subscription',
                'monthly_job_credits' => 0,
                'features'            => $features,
                'paystack_plan_code'  => '',
                'is_active'           => 1,
                'created_at'          => date('Y-m-d H:i:s'),
                'updated_at'          => date('Y-m-d H:i:s'),
            ]);
        }

        // 2. Insert bundles if the table is empty
        $bundleCount = $this->db->table('plan_bundles')->countAllResults();
        if ($bundleCount === 0) {
            $now = date('Y-m-d H:i:s');
            $this->db->table('plan_bundles')->insertBatch([
                ['name' => 'Starter Bundle',  'slug' => 'starter',  'job_credits' => 1,  'price' => 6000,  'price_per_credit' => 6000, 'is_best_value' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Growth Bundle',   'slug' => 'growth',   'job_credits' => 3,  'price' => 15000, 'price_per_credit' => 5000, 'is_best_value' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Gold Bundle',     'slug' => 'gold',     'job_credits' => 5,  'price' => 22500, 'price_per_credit' => 4500, 'is_best_value' => 1, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
                ['name' => 'Diamond Bundle',  'slug' => 'diamond',  'job_credits' => 10, 'price' => 40000, 'price_per_credit' => 4000, 'is_best_value' => 0, 'is_active' => 1, 'created_at' => $now, 'updated_at' => $now],
            ]);
        }
    }

    public function down()
    {
        $this->db->table('plans')->where('plan_type', 'subscription')->where('code', 'business_pro')->delete();
        $this->db->table('plan_bundles')->truncate();
    }
}
