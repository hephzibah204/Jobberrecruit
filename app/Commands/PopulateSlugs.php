<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\JobModel;

class PopulateSlugs extends BaseCommand
{
    protected $group       = 'SEO';
    protected $name        = 'seo:populate-slugs';
    protected $description = 'Populates slugs for jobs, categories, industries, and location hubs in the database.';
    protected $options     = [
        '--force' => 'Force regeneration of all slugs across all entities even if already populated.',
    ];

    public function run(array $params)
    {
        $force = array_key_exists('force', $params) || CLI::getOption('force') !== null;

        CLI::write("========================================", 'cyan');
        CLI::write("   POPULATING SEO SLUGS ACROSS DB       ", 'yellow');
        CLI::write("   (Jobs, Categories, Industries, States)", 'yellow');
        CLI::write("========================================", 'cyan');
        if ($force) {
            CLI::write(" Mode: FORCE REGENERATION\n", 'magenta');
        } else {
            CLI::write(" Mode: POPULATE MISSING\n", 'white');
        }

        $results = \App\Services\SeoSlugGenerator::populateAll($force);

        CLI::write("[1] Locations (States):", 'white');
        CLI::write("    Total: {$results['locations']['total']} | Updated: {$results['locations']['updated']}", 'green');

        CLI::write("\n[2] Categories (job_categories):", 'white');
        CLI::write("    Total: {$results['categories']['total']} | Updated: {$results['categories']['updated']}", 'green');

        CLI::write("\n[3] Industries (industries):", 'white');
        CLI::write("    Total: {$results['industries']['total']} | Updated: {$results['industries']['updated']}", 'green');

        CLI::write("\n[4] Jobs (jobs):", 'white');
        CLI::write("    Total: {$results['jobs']['total']} | Updated: {$results['jobs']['updated']}", 'green');

        CLI::write("\n========================================", 'cyan');
        CLI::write("  ALL SLUGS POPULATED SUCCESSFULLY!     ", 'yellow');
        CLI::write("========================================\n", 'cyan');
    }
}
