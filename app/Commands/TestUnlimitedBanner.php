<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\CreditService;
use App\Models\EmployerModel;
use App\Models\UserModel;

class TestUnlimitedBanner extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:unlimited';
    protected $description = 'Test CreditService hasUnlimitedAccess for all employers';

    public function run(array $params)
    {
        $creditService = new CreditService();
        $employerModel = model(EmployerModel::class);
        $userModel     = model(UserModel::class);

        $employers = $employerModel->findAll();
        CLI::write("Found " . count($employers) . " employers:", "yellow");

        foreach ($employers as $emp) {
            $user = $userModel->find($emp->user_id);
            $hasUnl = $creditService->hasUnlimitedAccess($emp->user_id);
            $credits = $creditService->getAvailableCredits($emp->user_id);
            CLI::write("Emp ID: {$emp->id} | User ID: {$emp->user_id} | Email: " . ($user ? $user->email : 'N/A') . " | Unlimited: " . ($hasUnl ? 'YES (TRUE)' : 'NO (FALSE)') . " | Credits: {$credits}");
        }
    }
}
