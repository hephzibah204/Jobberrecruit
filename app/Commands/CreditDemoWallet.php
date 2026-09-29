<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\WalletService;

class CreditDemoWallet extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:credit-demo';
    protected $description = 'Credit user 36 wallet with 20,000';

    public function run(array $params)
    {
        $walletService = new WalletService();
        $walletService->credit(
            36,
            20000.00,
            'admin_test',
            'REF_TEST_' . time(),
            null,
            'Test credit for wallet unlock'
        );
        CLI::write("User 36 credited ₦20,000 successfully!", "green");
    }
}
