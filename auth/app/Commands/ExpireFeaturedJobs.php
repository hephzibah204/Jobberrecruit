<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\JobModel;

class ExpireFeaturedJobs extends BaseCommand
{
    protected $group       = 'Jobs';
    protected $name        = 'jobs:expire-featured';
    protected $description = 'Automatically expire featured jobs whose promotion period has ended';

    public function run(array $params)
    {
        $jobModel = model(JobModel::class);

        $expiredCount = $jobModel
            ->where('is_featured', 1)
            ->where('featured_until <=', date('Y-m-d H:i:s'))
            ->set(['featured' => 0, 'featured_until' => null])
            ->update();

        if ($expiredCount === false) {
            CLI::error('Failed to expire featured jobs.');
            return;
        }

        if ($expiredCount > 0) {
            CLI::write("Successfully expired {$expiredCount} featured job(s).", 'green');
        } else {
            CLI::write('No featured jobs to expire.', 'yellow');
        }
    }
}
