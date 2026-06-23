<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckImages extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'check:images';
    protected $description = 'Check image paths in database';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $employers = $db->query("SELECT id, logo FROM employers WHERE logo IS NOT NULL AND logo != '' LIMIT 5")->getResultArray();
        $candidates = $db->query("SELECT id, profile_picture FROM job_seekers WHERE profile_picture IS NOT NULL AND profile_picture != '' LIMIT 5")->getResultArray();
        
        CLI::write("Employers:");
        print_r($employers);
        CLI::write("Candidates:");
        print_r($candidates);
    }
}
