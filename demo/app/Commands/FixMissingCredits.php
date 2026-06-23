<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class FixMissingCredits extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'fix:missing_credits';
    protected $description = 'Retroactively add missing credits for active monthly subscriptions';

    public function run(array $params)
    {
        $db = db_connect();

        $subs = $db->table('user_subscriptions')
            ->where('is_active', 1)
            ->where('ends_at >', date('Y-m-d H:i:s'))
            ->get()->getResultArray();

        CLI::write("Active Subs: " . count($subs));

        foreach ($subs as $sub) {
            $planId = $sub['plan_id'];
            $userId = $sub['user_id'];
            
            $plan = $db->table('subscription_plans')->where('id', $planId)->get()->getRowArray();
            if (!$plan) {
                $plan = $db->table('plans')->where('id', $planId)->get()->getRowArray();
            }
            if (!$plan) continue;
            
            $credits = $plan['monthly_job_credits'] ?? 0;
            if ($credits <= 0) continue;
            
            $wallet = $db->table('job_credit_wallets')->where('user_id', $userId)->where('source', 'subscription')->get()->getResultArray();
            
            if (empty($wallet)) {
                CLI::write("MISSING CREDITS for User $userId! Inserting $credits credits...", "yellow");
                
                $db->table('job_credit_wallets')->insert([
                    'user_id' => $userId,
                    'credits' => $credits,
                    'source' => 'subscription',
                    'expires_at' => $sub['ends_at'],
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s'),
                ]);
                
                $db->table('job_credit_transactions')->insert([
                    'user_id' => $userId,
                    'type' => 'credit_in',
                    'credits' => $credits,
                    'reference' => 'retroactive_fix_' . $sub['id'],
                    'description' => 'Subscription credits added (Retroactive fix)',
                    'created_at' => date('Y-m-d H:i:s'),
                ]);
            }
        }

        CLI::write("Finished check.", "green");
    }
}
