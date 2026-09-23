<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class ResetPassword extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:reset-pass';
    protected $description = 'Reset password for demo.employer@example.com to Password123!';

    public function run(array $params)
    {
        $pass = 'Password123!';
        $hash = service('passwords')->hash($pass);

        $db = \Config\Database::connect();
        $db->table('auth_identities')
            ->where('user_id', 36)
            ->where('type', 'email_password')
            ->update(['secret2' => $hash]);

        CLI::write("Password for user 36 (demo.employer@example.com) reset to 'Password123!'", "green");
    }
}
