<?php
namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Services\EmailSuppressionService;

class EmailSuppress extends BaseCommand
{
    protected $group = 'Email';
    protected $name = 'email:suppress';
    protected $description = 'Suppress confirmed permanently bounced addresses from a text file (one address per line).';
    protected $usage = 'email:suppress <file>';
    public function run(array $params)
    {
        $path = $params[0] ?? '';
        if (!is_file($path) || !is_readable($path)) {
            CLI::error('Provide a readable file of confirmed hard-bounced addresses.');
            return EXIT_ERROR;
        }
        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        $addresses = array_unique(array_map(static fn ($email) => strtolower(trim($email)), $lines));
        foreach ($addresses as $email) {
            if (!filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($email) > 254) {
                CLI::error('Invalid address in input. No addresses were imported.');
                return EXIT_ERROR;
            }
        }
        $db = db_connect();
        $db->transBegin();
        try {
            $service = new EmailSuppressionService($db);
            foreach ($addresses as $email) $service->suppress($email, 'imported_hard_bounce');
            if (!$db->transStatus()) throw new \RuntimeException('Suppression import failed.');
            $db->transCommit();
            CLI::write(count($addresses) . ' addresses suppressed.', 'green');
        } catch (\Throwable $e) {
            $db->transRollback();
            CLI::error('Import failed. Run migrations and check database access.');
            return EXIT_ERROR;
        }
        return EXIT_SUCCESS;
    }
}
