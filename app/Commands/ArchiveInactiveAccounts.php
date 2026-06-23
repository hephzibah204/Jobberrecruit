<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\UserModel;
use App\Models\EmployerModel;
use App\Models\JobSeekerModel;

class ArchiveInactiveAccounts extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'accounts:archive';
    protected $description = 'Archive or remove accounts inactive for more than 3 months.';

    public function run(array $params)
    {
        $months = 3;
        $cutoffDate = date('Y-m-d H:i:s', strtotime("-{$months} months"));
        
        CLI::write("Scanning for accounts inactive since {$cutoffDate}...", 'yellow');

        $db = \Config\Database::connect();
        
        // 1. Identify inactive users (registered > 3 months ago and last_active < 3 months ago)
        // Shield users have 'created_at' and we added 'last_active' in AuthController
        $inactiveUsers = $db->table('users')
            ->where('created_at <', $cutoffDate)
            ->groupStart()
                ->where('last_active <', $cutoffDate)
                ->orWhere('last_active', null)
            ->groupEnd()
            ->where('active', 1) // Only active ones
            ->get()
            ->getResult();

        $count = count($inactiveUsers);
        CLI::write("Found {$count} inactive accounts.", 'cyan');

        if ($count === 0) {
            CLI::write("No accounts to archive.", 'green');
            return;
        }

        foreach ($inactiveUsers as $user) {
            CLI::write("Archiving user: {$user->username} (ID: {$user->id})...");
            
            // Method: Soft Archival (Deactivate and mark as archived)
            // We'll update the 'active' status to 0
            $db->table('users')
                ->where('id', $user->id)
                ->update(['active' => 0]);
                
            // Log archival
            log_message('info', "Account archived due to inactivity: User ID {$user->id}, Username: {$user->username}");
        }

        CLI::write("Maintenance completed successfully.", 'green');
    }
}
