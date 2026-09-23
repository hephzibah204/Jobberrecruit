<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\NewsletterModel;
use App\Controllers\NewsletterController;

class ProcessScheduledNewsletters extends BaseCommand
{
    protected $group       = 'Newsletter';
    protected $name        = 'newsletter:process-scheduled';
    protected $description = 'Process and blast scheduled newsletter campaigns';

    public function run(array $params)
    {
        $newsletterModel = new NewsletterModel();
        $now = date('Y-m-d H:i:s');

        // Fetch newsletters scheduled for now or earlier that haven't been sent
        $scheduled = $newsletterModel
            ->where('status', 'draft')
            ->where('scheduled_at <=', $now)
            ->where('scheduled_at IS NOT NULL')
            ->findAll();

        if (empty($scheduled)) {
            CLI::write('No scheduled newsletters to process.', 'white');
            return;
        }

        CLI::write('Processing ' . count($scheduled) . ' scheduled newsletter(s)...', 'cyan');

        // We use the controller's logic to maintain consistency
        $controller = new NewsletterController();

        foreach ($scheduled as $newsletter) {
            CLI::write("Blasting campaign: {$newsletter->title}...", 'yellow');
            
            // Note: Since we are in CLI, we need to handle the redirect response
            try {
                $controller->sendNewsletter($newsletter->id);
                CLI::write("Campaign #{$newsletter->id} successfully queued.", 'green');
            } catch (\Exception $e) {
                CLI::write("Error processing campaign #{$newsletter->id}: " . $e->getMessage(), 'red');
            }
        }

        CLI::write('Scheduled newsletter processing completed.', 'green');
    }
}
