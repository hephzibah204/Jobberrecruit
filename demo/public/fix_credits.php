<?php
// c:\Users\hephz\Documents\CODEBASE\Jobberrecruit\demo\public\fix_credits.php

require_once '../app/Config/Paths.php';
$paths = new Config\Paths();
require_once $paths->systemDirectory . '/bootstrap.php';

$db = db_connect();

$subs = $db->table('user_subscriptions')
    ->where('is_active', 1)
    ->where('ends_at >', date('Y-m-d H:i:s'))
    ->get()->getResultArray();

echo "Active Subs: " . count($subs) . "\n";

foreach ($subs as $sub) {
    $planId = $sub['plan_id'];
    $userId = $sub['user_id'];
    
    $plan = $db->table('plans')->where('id', $planId)->get()->getRowArray();
    if (!$plan) continue;
    
    $credits = $plan['monthly_job_credits'];
    echo "User $userId has active plan '{$plan['name']}' with $credits monthly credits.\n";
    
    $wallet = $db->table('job_credit_wallets')->where('user_id', $userId)->where('source', 'subscription')->get()->getResultArray();
    
    if (empty($wallet)) {
        echo "MISSING CREDITS for User $userId! Inserting $credits credits...\n";
        
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
            'reference' => 'retroactive_fix',
            'description' => 'Subscription credits added (Retroactive fix)',
            'created_at' => date('Y-m-d H:i:s'),
        ]);
        
        echo "Done.\n";
    } else {
        echo "User $userId already has wallet entries.\n";
        foreach ($wallet as $w) {
            echo " - {$w['credits']} credits (Expires: {$w['expires_at']})\n";
        }
    }
}
