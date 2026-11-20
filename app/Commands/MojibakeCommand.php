<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class MojibakeCommand extends BaseCommand
{
    protected $group       = 'Maintenance';
    protected $name        = 'mojibake:fix';
    protected $description = 'Removes all mojibake from files and database tables.';
    protected $usage       = 'mojibake:fix [--scan-only]';
    protected $options     = [
        '--scan-only' => 'Only scan and report without writing changes.',
    ];

    private array $replacements = [
        // Naira currency symbol (double-encoded E2 82 A6 in CP1252)
        'â‚¦' => '₦',
        'Â₦'  => '₦',

        // Double horizontal lines and arrows
        "\xC3\xA2\xE2\x80\xA2\xC2\x90" => '═', // â•  (corrupted ═)
        "\xC3\xA2\xE2\x80\xA0\xE2\x80\x99" => '→', // â†’ (corrupted →)

        // Box drawing dashes (e.g. ── in comments)
        'â”€' => '─',

        // Dashes & punctuation
        'â€”' => '—', // em dash
        'â€“' => '–', // en dash
        'â€¦' => '…', // horizontal ellipsis
        'â€¢' => '•', // bullet point
        'â„¢' => '™', // trademark

        // Quotes and apostrophes
        'â€™' => "'", // right single quote / apostrophe
        'â€˜' => "'", // left single quote
        'â€œ' => '"', // left double quote
        'â€ ' => '"', // right double quote variant
        'â€ ' => '"',
        'â€š' => "'", // low single quote
        'â€ž' => '"', // low double quote

        // Degree and symbols
        'Â°'  => '°',
        'Â·'  => '·', // middle dot
        'Â©'  => '©',
        'Â®'  => '®',
        'Â±'  => '±',
        'Â«'  => '«',
        'Â»'  => '»',
        'Â£'  => '£',
        'Â€'  => '€',

        // Accented characters
        'Ã©'  => 'é',
        'Ã¨'  => 'è',
        'Ãª'  => 'ê',
        'Ã«'  => 'ë',
        'Ã '  => 'à',
        'Ã¡'  => 'á',
        'Ã¢'  => 'â',
        'Ã§'  => 'ç',
        'Ã®'  => 'î',
        'Ã¯'  => 'ï',
        'Ã´'  => 'ô',
        'Ã¶'  => 'ö',
        'Ã¹'  => 'ù',
        'Ãº'  => 'ú',
        'Ã»'  => 'û',
        'Ã¼'  => 'ü',
        'Ã±'  => 'ñ',
        'Ã³'  => 'ó',
        'Ã²'  => 'ò',
        'Ã'   => 'í',
        'Ã—'  => '×',

        // Stray C2 non-breaking space artifact
        'Â '  => ' ',

        // Unicode replacement character in comments/text
        "\xEF\xBF\xBD" => '—',
    ];

    public function run(array $params)
    {
        $scanOnly = array_key_exists('scan-only', $params) || CLI::getOption('scan-only');
        $doFix = !$scanOnly;

        CLI::write("========================================", 'yellow');
        CLI::write($doFix ? "  REMOVING ALL MOJIBAKE (FILES & DB)" : "  SCANNING FOR MOJIBAKE", 'yellow');
        CLI::write("========================================\n", 'yellow');

        $this->processFiles($doFix);
        $this->processDatabase($doFix);

        CLI::write("\n========================================", 'green');
        CLI::write($doFix ? "All mojibake removed successfully!" : "Scan completed.", 'green');
        CLI::write("========================================", 'green');
    }

    private function processFiles(bool $doFix): void
    {
        CLI::write("[1] Processing Codebase Files...", 'cyan');
        $root = FCPATH;
        $allowedExts = ['php', 'html', 'js', 'css', 'json', 'sql', 'md', 'txt'];

        $dirIterator = new \RecursiveDirectoryIterator($root, \RecursiveDirectoryIterator::SKIP_DOTS);
        $filterIterator = new \RecursiveCallbackFilterIterator($dirIterator, function ($file, $key, $iterator) {
            if ($iterator->hasChildren()) {
                $fn = $file->getFilename();
                return !in_array($fn, ['.git', 'vendor', 'node_modules', 'writable', 'system', 'ftp_upload', 'storage']);
            }
            return true;
        });

        $iterator = new \RecursiveIteratorIterator($filterIterator);

        $totalFound = 0;
        $filesChanged = 0;
        $bomsRemoved = 0;

        foreach ($iterator as $file) {
            $path = $file->getPathname();
            $relPath = str_replace($root, '', $path);
            $filename = $file->getFilename();

            // Skip self and temporary scripts and vendor plugins with binary/transliteration data
            if ($filename === 'MojibakeCommand.php' || str_starts_with($filename, 'deep_mojibake') || str_starts_with($filename, 'scan_') || str_starts_with($filename, 'check_') || str_starts_with($filename, 'find_') || str_starts_with($filename, 'inspect_')) {
                continue;
            }
            if (str_contains($relPath, 'js/plugins/') || str_contains($relPath, 'js\\plugins\\')) {
                continue;
            }
            if (str_contains($relPath, 'cdn.jsdelivr.net') || str_contains($relPath, 'plugins/select2') || str_contains($relPath, 'plugins\\select2')) {
                continue;
            }
            if (str_contains($relPath, 'pdfmake') || str_contains($relPath, 'ckeditor') || str_contains($relPath, 'typicons')) {
                continue;
            }

            $ext = strtolower($file->getExtension());
            if (!in_array($ext, $allowedExts)) continue;

            $content = file_get_contents($path);
            if ($content === false) continue;

            $fileHasMatch = false;
            $newContent = $content;

            // Check for UTF-8 BOM
            if (str_starts_with($newContent, "\xEF\xBB\xBF")) {
                $fileHasMatch = true;
                $bomsRemoved++;
                CLI::write("  - $relPath: found UTF-8 BOM at start of file", 'light_red');
                if ($doFix) {
                    $newContent = substr($newContent, 3);
                }
            }

            foreach ($this->replacements as $search => $replace) {
                if (strpos($newContent, $search) !== false) {
                    $count = substr_count($newContent, $search);
                    $totalFound += $count;
                    $fileHasMatch = true;

                    CLI::write("  - $relPath: found " . bin2hex($search) . " ($count times)", 'light_red');

                    if ($doFix) {
                        $newContent = str_replace($search, $replace, $newContent);
                    }
                }
            }

            if ($fileHasMatch && $doFix) {
                file_put_contents($path, $newContent);
                $filesChanged++;
                CLI::write("    -> Fixed and saved: $relPath", 'green');
            }
        }

        CLI::write("Files: Found $totalFound mojibake occurrences and $bomsRemoved BOMs." . ($doFix ? " Cleaned $filesChanged files." : ""), 'yellow');
    }

    private function processDatabase(bool $doFix): void
    {
        CLI::write("\n[2] Processing Database Tables...", 'cyan');
        try {
            $db = db_connect();
            $tables = $db->listTables();
        } catch (\Throwable $e) {
            CLI::error("Database connection error: " . $e->getMessage());
            return;
        }

        $totalDbFound = 0;
        $rowsUpdated = 0;

        foreach ($tables as $table) {
            try {
                $fields = $db->getFieldData($table);
            } catch (\Throwable $e) {
                continue;
            }

            $textFields = [];
            $pk = 'id';
            foreach ($fields as $field) {
                if ($field->primary_key) {
                    $pk = $field->name;
                }
                $type = strtolower($field->type);
                if (in_array($type, ['varchar', 'text', 'mediumtext', 'longtext', 'tinytext', 'char', 'string'])) {
                    $textFields[] = $field->name;
                }
            }

            if (empty($textFields)) continue;

            $rows = $db->table($table)->get()->getResultArray();
            foreach ($rows as $row) {
                $rowUpdates = [];

                foreach ($textFields as $col) {
                    $val = $row[$col] ?? '';
                    if (!is_string($val) || $val === '') continue;

                    $newVal = $val;
                    $colHasMatch = false;

                    foreach ($this->replacements as $search => $replace) {
                        if (strpos($newVal, $search) !== false) {
                            $count = substr_count($newVal, $search);
                            $totalDbFound += $count;
                            $colHasMatch = true;

                            $pkVal = $row[$pk] ?? 'unknown';
                            CLI::write("  - Table `{$table}` (PK $pk = {$pkVal}, col: `{$col}`): found " . bin2hex($search) . " ($count times)", 'light_red');

                            if ($doFix) {
                                $newVal = str_replace($search, $replace, $newVal);
                            }
                        }
                    }

                    // Special check: Math subtraction with corrupted '?' (e.g. "4,000 ? 1,000 = 3,000")
                    if ($table === 'questions' && $col === 'explanation') {
                        if (preg_match('/(\d+(?:,\d+)*)\s*\?\s*(\d+(?:,\d+)*)\s*=/', $newVal)) {
                            $fixedMath = preg_replace('/(\d+(?:,\d+)*)\s*\?\s*(\d+(?:,\d+)*)\s*=/', '$1 - $2 =', $newVal);
                            if ($fixedMath !== $newVal) {
                                $totalDbFound++;
                                $colHasMatch = true;
                                $pkVal = $row[$pk] ?? 'unknown';
                                CLI::write("  - Table `{$table}` (PK $pk = {$pkVal}, col: `{$col}`): fixed subtraction '?' -> '-'", 'light_red');
                                if ($doFix) {
                                    $newVal = $fixedMath;
                                }
                            }
                        }
                    }

                    if ($colHasMatch && $doFix) {
                        $rowUpdates[$col] = $newVal;
                    }
                }

                if (!empty($rowUpdates) && $doFix && isset($row[$pk])) {
                    $db->table($table)->where($pk, $row[$pk])->update($rowUpdates);
                    $rowsUpdated++;
                }
            }
        }

        CLI::write("Database: Found $totalDbFound mojibake occurrences." . ($doFix ? " Updated $rowsUpdated rows." : ""), 'yellow');
    }
}
