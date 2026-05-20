<?php

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use App\Models\JobQueueModel;

class QueueProcessor extends BaseCommand
{
    protected $group       = 'Queue';
    protected $name        = 'queue:work';
    protected $description = 'Processes pending jobs in the queue.';

    public function run(array $params)
    {
        $queueModel = new JobQueueModel();
        $limit = $params[0] ?? 50; // Process 50 jobs per run by default

        $jobs = $queueModel->getPending($limit);

        if (empty($jobs)) {
            CLI::write('No pending jobs in the queue.', 'yellow');
            return;
        }

        CLI::write('Processing ' . count($jobs) . ' jobs...', 'cyan');

        foreach ($jobs as $job) {
            $this->processJob($job, $queueModel);
        }

        CLI::write('Queue processing finished.', 'green');
    }

    protected function processJob($job, $queueModel)
    {
        $queueModel->update($job->id, ['status' => 'processing']);
        
        $payload = json_decode($job->payload, true);
        $type = $payload['type'];
        $data = $payload['data'];

        $success = false;
        $error = null;

        try {
            switch ($type) {
                case 'newsletter_email':
                    $success = $this->sendEmail($data['email'], $data['subject'], $data['content']);
                    break;
                case 'transactional_email':
                    $success = $this->sendTransactionalEmail($data);
                    break;
                // Add more cases here as needed
                default:
                    $error = "Unknown job type: {$type}";
            }
        } catch (\Exception $e) {
            $error = $e->getMessage();
        }

        if ($success) {
            $queueModel->update($job->id, ['status' => 'completed']);
            CLI::write("Job #{$job->id} ({$type}) completed.", 'green');
        } else {
            $attempts = $job->attempts + 1;
            $status = ($attempts >= 3) ? 'failed' : 'pending';
            
            $queueModel->update($job->id, [
                'status' => $status,
                'attempts' => $attempts,
                'error' => $error ?? 'Unknown error',
                'available_at' => date('Y-m-d H:i:s', time() + (60 * $attempts)) // Exponential backoff
            ]);
            
            CLI::write("Job #{$job->id} ({$type}) " . ($status === 'failed' ? 'failed' : 'retrying') . ": {$error}", 'red');
        }
    }

    protected function sendEmail($to, $subject, $content)
    {
        \Config\Services::$bypassQueue = true;
        $email = \Config\Services::email(false);
        \Config\Services::$bypassQueue = false;

        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($content);
        
        // Ensure email is sent as HTML if needed
        $email->setMailType('html');

        if ($email->send()) {
            return true;
        } else {
            // Log full error for debugging
            log_message('error', 'Queue Email Error: ' . $email->printDebugger(['headers']));
            return false;
        }
    }

    protected function sendTransactionalEmail($data)
    {
        \Config\Services::$bypassQueue = true;
        $email = \Config\Services::email(false);
        \Config\Services::$bypassQueue = false;

        $email->setTo($data['to']);
        $email->setSubject($data['subject']);
        $email->setMessage($data['message']);

        if (!empty($data['alt_message'])) {
            $email->setAltMessage($data['alt_message']);
        }
        if (!empty($data['reply_to'])) {
            $email->setReplyTo($data['reply_to']);
        }
        if (!empty($data['mail_type'])) {
            $email->setMailType($data['mail_type']);
        }
        if (!empty($data['headers']) && is_array($data['headers'])) {
            foreach ($data['headers'] as $key => $val) {
                $email->setHeader($key, $val);
            }
        }

        if ($email->send()) {
            return true;
        } else {
            log_message('error', 'Queue Transactional Email Error: ' . $email->printDebugger(['headers']));
            return false;
        }
    }
}
