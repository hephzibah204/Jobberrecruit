<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckUsers extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:users';
    protected $description = 'List all employer users and their credentials';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $users = $db->table('users')->where('user_type', 'employer')->get()->getResultArray();
        CLI::write("Employer Users:", "yellow");
        foreach ($users as $u) {
            $identity = $db->table('auth_identities')->where('user_id', $u['id'])->get()->getRowArray();
            $secret = $identity['secret'] ?? 'N/A';
            CLI::write("ID: {$u['id']} | Username: " . ($u['username'] ?? '') . " | Email/Secret: {$secret}");
        }
    }
}
