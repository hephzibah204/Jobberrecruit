<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DebugSubs extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'debug:subs';
    protected $description = 'Debug subscriptions';

    public function run(array $params)
    {
        $db = db_connect();
        $subs = $db->table('user_subscriptions')->get()->getResultArray();
        CLI::write("User Subscriptions:", "yellow");
        print_r($subs);

        $wallet = $db->table('job_credit_wallet')->get()->getResultArray();
        CLI::write("Job Credit Wallet:", "yellow");
        print_r($wallet);
    }
}
