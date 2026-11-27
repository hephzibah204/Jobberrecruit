<?php

namespace App\Commands;

use App\Services\DatabaseBackupService;
use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class BackupDatabase extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'backup:database';
    protected $description = 'Create a full database backup (schema + data) in writable/backups.';

    public function run(array $params)
    {
        try {
            $result = (new DatabaseBackupService())->createBackup();
        } catch (\Throwable $e) {
            CLI::error($e->getMessage());
            return;
        }

        CLI::write('Backup created: ' . $result['filename'] . ' (' . $result['tables'] . ' tables, ' . $result['rows'] . ' rows)', 'green');
    }
}
