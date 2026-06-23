<?php
require 'app/Config/Paths.php';
$paths = new \Config\Paths();
require $paths->systemDirectory . '/bootstrap.php';

// Mock session and auth for CLI if needed, or bypass
// Actually, let's just trigger the Queue directly for a mock newsletter.
$db = \Config\Database::connect();
$newsletter = $db->query("SELECT * FROM newsletters LIMIT 1")->getRow();

if (!$newsletter) {
    echo "No newsletters found.\n";
    exit;
}

echo "Using newsletter ID {$newsletter->id} ({$newsletter->title})\n";

// Force queueing to all active subscribers
$subs = $db->query("SELECT * FROM newsletter_subscribers WHERE is_active = 1")->getResult();
echo "Found " . count($subs) . " active subscribers.\n";

$queueModel = new \App\Models\JobQueueModel();

foreach ($subs as $sub) {
    $renderedContent = "<h1>Test Email from Newsletter {$newsletter->title}</h1><p>Hello {$sub->email}</p>";
    $queueModel->dispatchAndRun('newsletter_email', [
        'newsletter_id' => $newsletter->id,
        'email'         => $sub->email,
        'subject'       => "[TEST TO ALL] " . ($newsletter->subject ?? $newsletter->title),
        'content'       => $renderedContent
    ]);
    echo "Queued email for {$sub->email}\n";
}

echo "Emails queued successfully. If not sent, try running: php spark queue:work\n";
