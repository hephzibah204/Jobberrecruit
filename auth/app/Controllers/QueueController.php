<?php

namespace App\Controllers;

use App\Models\JobQueueModel;

class QueueController extends BaseController
{
    public function processQueue()
    {
        // Security check
        if (!auth()->loggedIn() || auth()->user()->user_type !== 'admin') {
            return $this->response->setJSON(['success' => false, 'message' => 'Unauthorized']);
        }

        $queueModel = new JobQueueModel();
        $jobs = $queueModel->getPending(50);

        if (empty($jobs)) {
            return $this->response->setJSON(['success' => true, 'message' => 'No pending jobs.']);
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
                
                if ($type === 'newsletter_email' && !empty($data['newsletter_id'])) {
                    $newsletterModel = new \App\Models\NewsletterModel();
                    $newsletterModel->incrementSent($data['newsletter_id'], 1);
                }
            } else {
                $attempts = $job->attempts + 1;
                $status = ($attempts >= 3) ? 'failed' : 'pending';
                
                $queueModel->update($job->id, [
                    'status' => $status,
                    'attempts' => $attempts,
                    'error' => $error ?? 'Unknown error',
                    'available_at' => date('Y-m-d H:i:s', time() + (60 * $attempts))
                ]);
                $failed++;
            }
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => "Processed $processed jobs successfully." . ($failed > 0 ? " ($failed failed)" : "")
        ]);
    }

    protected function sendEmail($to, $subject, $content)
    {
        \Config\Services::$bypassQueue = true;
        $email = \Config\Services::email(false);
        \Config\Services::$bypassQueue = false;

        $email->setTo($to);
        $email->setSubject($subject);
        $email->setMessage($content);
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

        return $email->send();
    }
}
