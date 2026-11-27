<?php

namespace App\Services;

/**
 * Creates full database backups (schema + data) as .sql files.
 *
 * Pure PHP over the framework database connection — no mysqldump or
 * other shell tools, which shared hosting cannot be relied on to
 * provide. The dump is streamed to a .tmp file in pages and renamed
 * into place only when complete, so a failed run never leaves a
 * half-written backup behind (the same write pattern the
 * db:schema-export command uses for its schema-only export).
 *
 * Files live in writable/backups/, which is not web-accessible:
 * writable/ carries a deny-all .htaccess and the site root .htaccess
 * blocks the writable path outright, and this service additionally
 * drops its own deny .htaccess + blank index.html into the folder.
 * Filenames carry a random suffix, and downloads are only ever served
 * by AdminController::downloadBackup() after the admin auth check.
 */
class DatabaseBackupService
{
    private const FILENAME_PATTERN = '/^jobberrecruit-backup-\d{8}-\d{6}-[0-9a-f]{8}\.sql$/';

    private \CodeIgniter\Database\BaseConnection $db;

    public function __construct(?\CodeIgniter\Database\BaseConnection $db = null)
    {
        $this->db = $db ?? \Config\Database::connect();
    }

    /**
     * Directory backup files are stored in, created on demand along
     * with the guard files that keep it unreachable over HTTP.
     */
    public function backupDirectory(): string
    {
        $dir = WRITEPATH . 'backups';

        if (! is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        if (! is_file($dir . '/.htaccess')) {
            file_put_contents($dir . '/.htaccess', "<IfModule authz_core_module>\n\tRequire all denied\n</IfModule>\n<IfModule !authz_core_module>\n\tDeny from all\n</IfModule>\n");
        }

        if (! is_file($dir . '/index.html')) {
            file_put_contents($dir . '/index.html', '');
        }

        return $dir;
    }

    /**
     * Write a full backup and return its details:
     * ['filename' => string, 'size' => int, 'tables' => int, 'rows' => int]
     *
     * @throws \RuntimeException when the dump cannot be completed; any
     *                           partial file is removed first.
     */
    public function createBackup(): array
    {
        $dir      = $this->backupDirectory();
        $filename = 'jobberrecruit-backup-' . date('Ymd-His') . '-' . bin2hex(random_bytes(4)) . '.sql';
        $path     = $dir . '/' . $filename;
        $tmpPath  = $path . '.tmp';

        $handle = fopen($tmpPath, 'wb');
        if ($handle === false) {
            throw new \RuntimeException('Unable to write backup file: ' . $tmpPath);
        }

        $tableCount = 0;

        try {
            $tables     = $this->tableList();
            $tableCount = count($tables);

            fwrite($handle, "-- JobberRecruit database backup\n");
            fwrite($handle, '-- Generated: ' . date('Y-m-d H:i:s') . "\n");
            fwrite($handle, '-- Database: ' . $this->db->getDatabase() . "\n");
            fwrite($handle, '-- Tables: ' . $tableCount . "\n\n");
            fwrite($handle, "SET NAMES utf8mb4;\n");
            fwrite($handle, "SET FOREIGN_KEY_CHECKS=0;\n\n");

            $totalRows = 0;
            foreach ($tables as $table) {
                $totalRows += $this->dumpTable($handle, $table['name'], $table['type'] === 'VIEW');
            }

            fwrite($handle, "SET FOREIGN_KEY_CHECKS=1;\n");
            fclose($handle);
            $handle = null;

            rename($tmpPath, $path);
        } catch (\Throwable $e) {
            if (is_resource($handle)) {
                fclose($handle);
            }
            if (is_file($tmpPath)) {
                unlink($tmpPath);
            }

            throw new \RuntimeException('Database backup failed: ' . $e->getMessage(), 0, $e);
        }

        return [
            'filename' => $filename,
            'size'     => (int) filesize($path),
            'tables'   => $tableCount,
            'rows'     => $totalRows,
        ];
    }

    /**
     * Existing backups, newest first.
     *
     * @return list<array{filename: string, size: int, size_human: string, created_at: string}>
     */
    public function listBackups(): array
    {
        $dir = $this->backupDirectory();

        $backups = [];
        foreach (scandir($dir) ?: [] as $entry) {
            if (preg_match(self::FILENAME_PATTERN, $entry) !== 1 || ! is_file($dir . '/' . $entry)) {
                continue;
            }

            $size      = (int) filesize($dir . '/' . $entry);
            $backups[] = [
                'filename'   => $entry,
                'size'       => $size,
                'size_human' => self::formatSize($size),
                'created_at' => date('Y-m-d H:i:s', (int) filemtime($dir . '/' . $entry)),
            ];
        }

        usort($backups, static fn ($a, $b) => [$b['created_at'], $b['filename']] <=> [$a['created_at'], $a['filename']]);

        return $backups;
    }

    /**
     * Absolute path for a backup filename, or null when the name is
     * not a well-formed backup name or the file does not exist. The
     * pattern check plus realpath containment make path traversal
     * impossible: only files generated by createBackup() resolve.
     */
    public function backupPath(string $filename): ?string
    {
        if (preg_match(self::FILENAME_PATTERN, $filename) !== 1) {
            return null;
        }

        $dir  = realpath($this->backupDirectory());
        $path = $dir === false ? false : realpath($dir . '/' . $filename);

        if ($dir === false || $path === false || ! is_file($path)) {
            return null;
        }

        return strpos($path, $dir . DIRECTORY_SEPARATOR) === 0 ? $path : null;
    }

    public function deleteBackup(string $filename): bool
    {
        $path = $this->backupPath($filename);

        return $path !== null && unlink($path);
    }

    /**
     * All tables and views in the current database, in the order
     * SHOW FULL TABLES returns them.
     *
     * @return list<array{name: string, type: string}>
     */
    private function tableList(): array
    {
        $tables = [];
        foreach ($this->db->query('SHOW FULL TABLES')->getResultArray() as $row) {
            $values   = array_values($row);
            $tables[] = [
                'name' => (string) $values[0],
                'type' => strtoupper((string) ($row['Table_type'] ?? $values[1] ?? 'BASE TABLE')),
            ];
        }

        return $tables;
    }

    /**
     * Stream one table (structure, then data in pages) to the open
     * backup file. Returns the number of data rows written; views
     * contribute their definition only, exactly as db:schema-export
     * treats them.
     *
     * @param resource $handle
     */
    private function dumpTable($handle, string $table, bool $isView): int
    {
        $quoted = '`' . str_replace('`', '``', $table) . '`';

        if ($isView) {
            $row = $this->db->query('SHOW CREATE VIEW ' . $quoted)->getRowArray();
            $sql = (string) ($row['Create View'] ?? array_values($row)[1] ?? '');
            if ($sql !== '') {
                fwrite($handle, $sql . ";\n\n");
            }

            return 0;
        }

        fwrite($handle, "-- --------------------------------------------------------\n");
        fwrite($handle, '-- Table: ' . $table . "\n");
        fwrite($handle, "-- --------------------------------------------------------\n\n");
        fwrite($handle, 'DROP TABLE IF EXISTS ' . $quoted . ";\n");

        $row = $this->db->query('SHOW CREATE TABLE ' . $quoted)->getRowArray();
        $sql = (string) ($row['Create Table'] ?? array_values($row)[1] ?? '');
        fwrite($handle, $sql . ";\n\n");

        // Column metadata drives value formatting: binary columns are
        // written as hex literals (the only lossless way to carry raw
        // bytes in a text .sql file), numeric columns unquoted, and
        // everything else escaped through the connection.
        $columns = $this->db->getFieldNames($table);
        $binary  = [];
        $numeric = [];
        foreach ($this->db->getFieldData($table) as $field) {
            $type = strtolower((string) $field->type);
            if (in_array($type, ['blob', 'tinyblob', 'mediumblob', 'longblob', 'binary', 'varbinary'], true)) {
                $binary[$field->name] = true;
            }
            if (in_array($type, ['int', 'tinyint', 'smallint', 'mediumint', 'bigint', 'decimal', 'float', 'double', 'year'], true)) {
                $numeric[$field->name] = true;
            }
        }

        $escapedColumns = [];
        foreach ($columns as $column) {
            $escapedColumns[] = '`' . str_replace('`', '``', $column) . '`';
        }
        $columnList = implode(', ', $escapedColumns);

        $rows     = 0;
        $offset   = 0;
        $pageSize = 500;
        while (true) {
            $batch = $this->db->query('SELECT * FROM ' . $quoted . ' LIMIT ' . $pageSize . ' OFFSET ' . $offset)->getResultArray();
            if ($batch === []) {
                break;
            }

            foreach (array_chunk($batch, 100) as $chunk) {
                $tuples = [];
                foreach ($chunk as $record) {
                    $values = [];
                    foreach ($columns as $column) {
                        $values[] = $this->formatValue($record[$column] ?? null, isset($binary[$column]), isset($numeric[$column]));
                    }
                    $tuples[] = '(' . implode(', ', $values) . ')';
                }
                fwrite($handle, 'INSERT INTO ' . $quoted . ' (' . $columnList . ') VALUES ' . implode(', ', $tuples) . ";\n");
            }

            $rows   += count($batch);
            $offset += $pageSize;
            if (count($batch) < $pageSize) {
                break;
            }
        }

        if ($rows > 0) {
            fwrite($handle, "\n");
        }

        return $rows;
    }

    private function formatValue($value, bool $binary, bool $numeric): string
    {
        if ($value === null) {
            return 'NULL';
        }

        if ($binary) {
            return $value === '' ? "''" : "X'" . bin2hex((string) $value) . "'";
        }

        if ($numeric && is_numeric($value)) {
            return (string) $value;
        }

        return (string) $this->db->escape($value);
    }

    private static function formatSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return number_format($bytes / 1048576, 2) . ' MB';
        }

        if ($bytes >= 1024) {
            return number_format($bytes / 1024, 1) . ' KB';
        }

        return $bytes . ' B';
    }
}
