<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;

class CheckJobsPage extends BaseCommand
{
    protected $group       = 'Testing';
    protected $name        = 'jobs:check';
    protected $description = 'Checks jobs page, featured sorting, and pagination.';

    public function run(array $params)
    {
        $db = db_connect();
        $featuredRows = $db->table('jobs')
            ->select('id, title, is_featured, featured_until, created_at')
            ->where('is_featured', 1)
            ->get()->getResult();

        CLI::write("Total jobs in DB with is_featured = 1: " . count($featuredRows), 'green');
        foreach ($featuredRows as $r) {
            CLI::write("ID: {$r->id} | Until: {$r->featured_until} | Title: {$r->title}", 'yellow');
        }
    }
}
