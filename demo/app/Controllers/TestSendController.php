<?php

namespace App\Controllers;

use App\Models\JobQueueModel;

class TestSendController extends BaseController
{
    public function index()
    {
        $db = \Config\Database::connect();
        $newsletter = $db->query("SELECT * FROM newsletters LIMIT 1")->getRow();

        if (!$newsletter) {
            return "No newsletters found.";
        }

        echo "Using newsletter ID {$newsletter->id} ({$newsletter->title})<br>";

        // Force queueing to all active subscribers
        $subs = $db->query("SELECT * FROM newsletter_subscribers WHERE is_active = 1")->getResult();
        echo "Found " . count($subs) . " active subscribers.<br>";

        $queueModel = new JobQueueModel();

        foreach ($subs as $sub) {
            $renderedContent = "<h1>Test Email from Newsletter {$newsletter->title}</h1><p>Hello {$sub->email}</p>";
            $queueModel->dispatchAndRun('newsletter_email', [
                'newsletter_id' => $newsletter->id,
                'email'         => $sub->email,
                'subject'       => "[TEST TO ALL] " . ($newsletter->subject ?? $newsletter->title),
                'content'       => $renderedContent
            ]);
            echo "Queued email for {$sub->email}<br>";
        }

        return "Emails queued successfully. They should be sent shortly via the QueueProcessor.";
    }
}
