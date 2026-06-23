<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DebugAllSubs extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'debug:allsubs';
    protected $description = 'Dump all subscriptions';

    public function run(array $params)
    {
        $db = db_connect();

        $subs = $db->table('user_subscriptions')->get()->getResultArray();

        CLI::write("All Subs: " . count($subs));
        foreach ($subs as $sub) {
            CLI::write("User ID: {$sub['user_id']} | Plan ID: {$sub['plan_id']} | Active: {$sub['is_active']} | Ends At: {$sub['ends_at']}");
        }
    }
}
