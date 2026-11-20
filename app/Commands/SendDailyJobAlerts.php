<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\JobAlertService;

class SendDailyJobAlerts extends BaseCommand
{
    protected $group = 'Jobs';
    protected $name = 'alerts:daily';
    protected $description = 'Send daily job alerts';

    public function run(array $params)
    {
        $service = new JobAlertService();
        $service->processAlerts('daily');

        CLI::write('Daily job alerts sent.', 'green');
    }
}
