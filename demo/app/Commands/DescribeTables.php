<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class DescribeTables extends BaseCommand
{
    protected $group       = 'Custom';
    protected $name        = 'db:describe';
    protected $description = 'Describe tables to find missing columns';

    public function run(array $params)
    {
        $db = \Config\Database::connect();
        
        $messages = $db->getFieldNames('messages');
        CLI::write("Messages columns: " . implode(', ', $messages));
        
        if ($db->tableExists('job_notifications')) {
            $notifications = $db->getFieldNames('job_notifications');
            CLI::write("Job Notifications columns: " . implode(', ', $notifications));
        } else {
            CLI::write("Job Notifications table does not exist!");
        }
    }
}
