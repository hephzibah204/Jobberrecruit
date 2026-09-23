<?php

namespace App\Commands\Server;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

/**
 * Launch the PHP development server
 *
 * Overrides the framework's serve command to fix Windows quoting.
 * On Windows, escapeshellarg() wraps paths in single quotes, which
 * cmd.exe doesn't recognize. This uses double quotes on Windows instead.
 *
 * @codeCoverageIgnore
 */
class Serve extends BaseCommand
{
    protected $group       = 'CodeIgniter';
    protected $name        = 'serve';
    protected $description = 'Launches the CodeIgniter PHP-Development Server.';
    protected $usage       = 'serve';
    protected $arguments   = [];

    protected $portOffset = 0;
    protected $tries      = 10;

    protected $options = [
        '--php'  => 'The PHP Binary [default: "PHP_BINARY"]',
        '--host' => 'The HTTP Host [default: "localhost"]',
        '--port' => 'The HTTP Host Port [default: "8080"]',
    ];

    public function run(array $params)
    {
        $php  = $this->escapeShellArg(CLI::getOption('php') ?? PHP_BINARY);
        $host = CLI::getOption('host') ?? 'localhost';
        $port = (int) (CLI::getOption('port') ?? 8080) + $this->portOffset;

        CLI::write('CodeIgniter development server started on http://' . $host . ':' . $port, 'green');
        CLI::write('Press Control-C to stop.');

        $docroot = $this->escapeShellArg(FCPATH);
        $rewrite = $this->escapeShellArg(SYSTEMPATH . 'rewrite.php');

        passthru($php . ' -S ' . $host . ':' . $port . ' -t ' . $docroot . ' ' . $rewrite, $status);

        if ($status !== EXIT_SUCCESS && $this->portOffset < $this->tries) {
            $this->portOffset++;
            $this->run($params);
        }
    }

    /**
     * Escape a shell argument.
     *
     * On Windows, escapeshellarg() wraps in single quotes which cmd.exe
     * doesn't recognize. We use double quotes instead on Windows.
     * On other platforms, we use the standard escapeshellarg().
     */
    private function escapeShellArg(string $value): string
    {
        if (PHP_OS_FAMILY === 'Windows') {
            // rtrim backslashes so trailing \ doesn't escape the closing quote
            $value = rtrim($value, '\\/');
            // Escape double quotes and wrap in double quotes
            return '"' . str_replace(['"', '%'], ['\\"', '%%'], $value) . '"';
        }

        return escapeshellarg($value);
    }
}
