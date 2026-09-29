<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckWallets extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:wallets';
    protected $description = 'Check wallet balances';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $wallets = $db->table('wallets')->get()->getResultArray();
        CLI::write("Wallets count: " . count($wallets), "yellow");
        foreach ($wallets as $w) {
            CLI::write("ID: {$w['id']} | User ID: {$w['user_id']} | Balance: {$w['balance']} | Locked: {$w['is_locked']}");
        }
    }
}
