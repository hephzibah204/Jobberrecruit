<?php

namespace App\Controllers;

use App\Models\JobQueueModel;

class CronController extends BaseController
{
    public function processQueue()
    {
        // Simple security check (optional, but good practice to prevent abuse)
        // You can pass a token e.g., /cron/process-queue?token=YOUR_SECRET_TOKEN
        $token = $this->request->getGet('token');
        $expectedToken = env('cron_token', 'jobber_cron_secret_123'); // Set this in your .env
        
        if ($token !== $expectedToken) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized cron request.']);
        }

        $queueModel = new JobQueueModel();
        $limit = 50; // Process 50 jobs per run
        
        $jobs = $queueModel->getPending($limit);
        
        if (empty($jobs)) {
            return $this->response->setJSON(['status' => 'success', 'message' => 'No pending jobs in the queue.']);
        }

        $processed = 0;
        $failed = 0;

        foreach ($jobs as $job) {
            $queueModel->update($job->id, ['status' => 'processing']);
            
            $payload = json_decode($job->payload, true);
            $type = $payload['type'];
            $data = $payload['data'];

            $success = false;
            $error = null;

            try {
                if ($type === 'newsletter_email') {
                    $success = $this->sendEmail($data['email'], $data['subject'], $data['content']);
                } elseif ($type === 'transactional_email') {
                    $success = $this->sendTransactionalEmail($data);
                } else {
                    $error = "Unknown job type: {$type}";
                }
            } catch (\Exception $e) {
                $error = $e->getMessage();
            }

            if ($success) {
                $queueModel->update($job->id, ['status' => 'completed']);
                $processed++;
            } else {
                $attempts = $job->attempts + 1;
                $status = ($attempts >= 3) ? 'failed' : 'pending';
                
                $queueModel->update($job->id, [
                    'status' => $status,
                    'attempts' => $attempts,
                    'error' => $error ?? 'Unknown error',
                    'available_at' => date('Y-m-d H:i:s', time() + (60 * $attempts)) // Exponential backoff
                ]);
                $failed++;
            }
        }

        return $this->response->setJSON([
            'status' => 'success', 
            'message' => 'Queue processing finished.',
            'jobs_processed' => $processed,
            'jobs_failed' => $failed
        ]);
    }

    /**
     * Archive accounts that haven't updated their profile in 3 months
     * Triggered via cron: /cron/archive-inactive-accounts?token=...
     */
    public function archiveInactiveAccounts()
    {
        $token = $this->request->getGet('token');
        $expectedToken = env('cron_token', 'jobber_cron_secret_123');
        
        if ($token !== $expectedToken) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Unauthorized.']);
        }

        $db = \Config\Database::connect();
        $threeMonthsAgo = date('Y-m-d H:i:s', strtotime('-3 months'));

        // We "archive" by setting status to 'inactive' or 'archived'
        // and logging the action. Safest method is to NOT delete, but flag.
        
        // 1. Update Employers
        $employerCount = $db->table('users')
            ->where('user_type', 'employer')
            ->where('status', 'active')
            ->where('updated_at <', $threeMonthsAgo)
            ->update(['status' => 'inactive', 'status_message' => 'Account archived due to 3 months of inactivity.']);

        // 2. Update Candidates
        $candidateCount = $db->table('users')
            ->where('user_type', 'candidate')
            ->where('status', 'active')
            ->where('updated_at <', $threeMonthsAgo)
            ->update(['status' => 'inactive', 'status_message' => 'Account archived due to 3 months of inactivity.']);

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Inactivity archiving complete.',
            'employers_archived' => $employerCount,
            'candidates_archived' => $candidateCount
        ]);
    }

    protected function sendEmail($to, $subject, $content)
    {
        $config = config('Email');
        \Config\Services::$bypassQueue = true;
        $email = \Config\Services::email(false);
        \Config\Services::$bypassQueue = false;

        $email->setFrom($config->fromEmail, $config->fromName);
        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($content);
        
        // Ensure email is sent as HTML
        $email->setMailType('html');

        if ($email->send()) {
            return true;
        } else {
            log_message('error', 'Queue Email Error: ' . $email->printDebugger(['headers']));
            return false;
        }
    }

    protected function sendTransactionalEmail($data)
    {
        $config = config('Email');
        \Config\Services::$bypassQueue = true;
        $email = \Config\Services::email(false);
        \Config\Services::$bypassQueue = false;

        $email->setFrom($config->fromEmail, $config->fromName);
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
