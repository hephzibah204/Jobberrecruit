<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckJobLogos extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:joblogos';
    protected $description = 'Inspect anonymous jobs';

    public function run(array $params)
    {
        $db = db_connect();
        $anonJobs = $db->table('jobs')->where('is_anonymous', 1)->get()->getResultArray();
        CLI::write("Anonymous jobs count: " . count($anonJobs));
        foreach ($anonJobs as $j) {
            CLI::write("ID: {$j['id']} | Title: {$j['title']} | Employer ID: {$j['employer_id']} | is_verified: {$j['is_verified']}");
        }
    }
}
