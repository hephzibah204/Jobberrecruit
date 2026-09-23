<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\EmployerModel;

class GrantUnlimited extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:grant-unlimited';
    protected $description = 'Grant unlimited access to employer ID 13';

    public function run(array $params)
    {
        $employerModel = model(EmployerModel::class);
        $employerModel->update(13, [
            'unlimited_access' => 1,
            'unlimited_until'  => '2099-12-31 23:59:59'
        ]);
        CLI::write("Employer 13 updated to unlimited_access = 1", "green");
    }
}
