<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\JobModel;

class PopulateSlugs extends BaseCommand
{
    protected $group       = 'SEO';
    protected $name        = 'seo:populate-slugs';
    protected $description = 'Populates missing slugs for all jobs in the database.';

    public function run(array $params)
    {
        $jobModel = new JobModel();
        $jobs = $jobModel->findAll();

        CLI::write("Processing " . count($jobs) . " jobs...", 'yellow');

        foreach ($jobs as $job) {
            // Trigger the beforeInsert/beforeUpdate hook by saving
            // We just need to update any field, or even just save the entity as is
            // since the hook will generate the slug if missing or updated.
            
            // To be safe and ensure the guide rules are applied, we'll force it.
            $jobModel->update($job->id, ['title' => $job->title]);
            
            CLI::write("Processed Job ID {$job->id}: " . CLI::color($job->title, 'green'));
        }

        CLI::write("All slugs populated successfully!", 'cyan');
    }
}
