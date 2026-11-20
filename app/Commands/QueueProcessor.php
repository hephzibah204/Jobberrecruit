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
        $result = (new \App\Services\EmailQueueWorker())->process(max(1, min(1000, (int) ($params[0] ?? 50))));
        CLI::write('Queue finished: ' . $result['jobs_processed'] . ' sent, ' . $result['jobs_failed'] . ' failed.', 'green');
    }

    protected function sendEmail($to, $subject, $content): array
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
            return ['success' => true];
        } else {
            $error = strip_tags($email->printDebugger(['headers']));
            log_message('error', 'Queue Email Error: ' . $error);
            return ['success' => false, 'error' => $error];
        }
    }

    protected function sendTransactionalEmail($data): array
    {
        \Config\Services::$bypassQueue = true;
        $email = \Config\Services::email(false);
        \Config\Services::$bypassQueue = false;

        $config = config('Email');
        $fromEmail = !empty($data['from_email']) ? $data['from_email'] : $config->fromEmail;
        $fromName  = !empty($data['from_name']) ? $data['from_name'] : $config->fromName;
        $email->setFrom($fromEmail, $fromName);

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
            return ['success' => true];
        } else {
            $error = strip_tags($email->printDebugger(['headers']));
            log_message('error', 'Queue Transactional Email Error: ' . $error);
            return ['success' => false, 'error' => $error];
        }
    }
}
