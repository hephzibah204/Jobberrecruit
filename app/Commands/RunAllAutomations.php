<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class RunAllAutomations extends BaseCommand
{
    protected $group = 'Cron';
    protected $name = 'cron:run-all-automations';
    protected $description = 'Run all automated email reminders and queue jobs via CLI';

    public function run(array $params)
    {
        CLI::write("Triggering all JobberRecruit automations...", 'yellow');

        $token = env('cron_token') ?: env('CRON_TOKEN', 'jobber_cron_secret_123');

        $incomingRequest = \Config\Services::incomingrequest(null, false);
        $incomingRequest->setGlobal('get', ['token' => $token]);
        $request = $incomingRequest->withMethod('GET');

        $_GET['token'] = $token;
        $_SERVER['REQUEST_METHOD'] = 'GET';
        $_SERVER['QUERY_STRING'] = 'token=' . $token;

        $response = service('response');
        $logger = service('logger');

        $controller = new \App\Controllers\CronController();
        $controller->initController($request, $response, $logger);

        try {
            $result = $controller->runAllAutomations();
            $body = $result->getBody();
            CLI::write("Automations finished successfully.", 'green');
            CLI::write($body, 'cyan');
        } catch (\Throwable $e) {
            CLI::error("Cron Automation Error: " . $e->getMessage());
            CLI::write($e->getTraceAsString(), 'red');
        }
    }
}
