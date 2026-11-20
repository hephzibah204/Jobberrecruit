<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckAllStates extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'states:check';
    protected $description = 'Checks Lagos description.';

    public function run(array $params)
    {
        $db = db_connect();
        CLI::write("--- TESTING CONTROLLER & VIEW RENDERING ---", 'yellow');

        $testSlugs = [
            'lagos-state'  => 'Lagos State with 7 jobs',
            'lagos'        => 'Lagos slug alias without -state',
            'fct-abuja'    => 'FCT Abuja with jobs',
            'fct-state'    => 'FCT State slug alias',
            'rivers-state' => 'Rivers State with jobs',
            'sokoto-state' => 'Sokoto State with 0 jobs (empty state test)',
        ];

        $appConfig = config('App');
        $uri = new \CodeIgniter\HTTP\SiteURI($appConfig, 'jobs-in-lagos-state');
        $incomingRequest = new \CodeIgniter\HTTP\IncomingRequest($appConfig, $uri, null, new \CodeIgniter\HTTP\UserAgent());
        \Config\Services::injectMock('request', $incomingRequest);

        $count = $db->table('states')->countAllResults();
        CLI::write("Total active states in system: $count", 'green');
    }
}
