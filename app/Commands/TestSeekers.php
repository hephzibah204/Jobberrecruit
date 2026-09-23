<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class TestSeekers extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'test:seekers';
    protected $description = 'List all job seekers';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        $seekers = $db->table('job_seekers')->get()->getResultArray();
        CLI::write("Job Seekers Count: " . count($seekers), "yellow");
        foreach ($seekers as $s) {
            CLI::write("ID: {$s['id']} | User ID: {$s['user_id']} | Name: " . ($s['full_name'] ?? 'N/A'));
        }
    }
}
