<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class SchemaExport extends BaseCommand
{
    protected $group = 'Database';
    protected $name = 'db:schema-export';
    protected $description = 'Exports the current database schema to a .sql file.';

    protected $usage = 'db:schema-export [--group groupName] [--output path]';

    protected $options = [
        '--group'  => 'Database group to connect with (defaults to configured default group).',
        '--output' => 'Output .sql file path (defaults to writable/schema.sql).',
    ];

    public function run(array $params)
    {
        $group = CLI::getOption('group');
        if ($group === null || $group === true) {
            $group = null;
        }

        $output = CLI::getOption('output');
        if ($output === null || $output === true) {
            $output = WRITEPATH . 'schema.sql';
        }

        $outputDir = dirname($output);
        if (! is_dir($outputDir)) {
            mkdir($outputDir, 0775, true);
        }

        $tmpOutput = $output . '.tmp';

        try {
            $db = $group ? db_connect((string) $group) : db_connect();

            $dbName = $db->getDatabase();
            $rows   = $db->query('SHOW FULL TABLES')->getResultArray();
            $total  = count($rows);

            $handle = fopen($tmpOutput, 'wb');
            if ($handle === false) {
                CLI::error('Unable to write to output file: ' . $tmpOutput);
                return;
            }

            fwrite($handle, "SET NAMES utf8mb4;\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            $i = 0;
            foreach ($rows as $row) {
                $i++;
                $tableName = (string) array_values($row)[0];
                $type      = strtoupper((string) ($row['Table_type'] ?? array_values($row)[1] ?? 'BASE TABLE'));

                if ($type === 'VIEW') {
                    $createRow = $db->query('SHOW CREATE VIEW `' . str_replace('`', '``', $tableName) . '`')->getRowArray();
                    $createSql = (string) ($createRow['Create View'] ?? array_values($createRow)[1] ?? '');
                    if ($createSql !== '') {
                        fwrite($handle, $createSql . ";\n\n");
                    }
                } else {
                    $createRow = $db->query('SHOW CREATE TABLE `' . str_replace('`', '``', $tableName) . '`')->getRowArray();
                    $createSql = (string) ($createRow['Create Table'] ?? array_values($createRow)[1] ?? '');
                    if ($createSql !== '') {
                        fwrite($handle, $createSql . ";\n\n");
                    }
                }

                if (($i % 25) === 0 || $i === $total) {
                    CLI::write('Exported ' . $i . '/' . $total);
                }
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);

            if (is_file($output)) {
                unlink($output);
            }
            rename($tmpOutput, $output);

            CLI::write('Exported schema from ' . $dbName . ' to ' . $output, 'green');
        } catch (\Throwable $e) {
            if (isset($handle) && is_resource($handle)) {
                fclose($handle);
            }
            if (is_file($tmpOutput)) {
                unlink($tmpOutput);
            }
            CLI::error($e->getMessage());
        }
    }
}
