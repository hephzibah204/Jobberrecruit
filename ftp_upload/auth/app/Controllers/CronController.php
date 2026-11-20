<?php

namespace App\Controllers;

use App\Models\JobQueueModel;

class CronController extends BaseController
{
    protected function validateCronToken(): bool
    {
        $token = $this->request->getGet('token') ?? ($_GET['token'] ?? null);
        $expectedToken = env('cron_token') ?: env('CRON_TOKEN', 'jobber_cron_secret_123');

        return !empty($token) && hash_equals(trim((string)$expectedToken), trim((string)$token));
    }

    public function processQueue()
    {
        if (!$this->validateCronToken()) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized cron request.']);
        }

        return $this->response->setJSON(array_merge(['status' => 'success'],
            (new \App\Services\EmailQueueWorker())->process(50)));
    }

    public function processEmailQueue()
    {
        if (!$this->validateCronToken()) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized cron request.']);
        }
        return $this->response->setJSON(array_merge(['status' => 'success'],
            (new \App\Services\EmailQueueWorker())->process(100, ['campaign_email', 'newsletter_email'])));
    }

    /**
     * Archive accounts that haven't updated their profile in 3 months
     * Triggered via cron: /cron/archive-inactive-accounts?token=...
     */
    public function archiveInactiveAccounts()
    {
        if (!$this->validateCronToken()) {
            return $this->response->setStatusCode(401)->setJSON(['status' => 'error', 'message' => 'Unauthorized cron request.']);
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

        $employerCount = $db->affectedRows();

        // 2. Update Candidates
        $candidateCount = $db->table('users')
            ->whereIn('user_type', ['job_seeker', 'candidate'])
            ->where('status', 'active')
            ->where('updated_at <', $threeMonthsAgo)
            ->update(['status' => 'inactive', 'status_message' => 'Account archived due to 3 months of inactivity.']);

        $candidateCount = $db->affectedRows();

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
        $email->setMailType('html');
        $email->SMTPTimeout = 5;

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

        $fromEmail = !empty($data['from_email']) ? $data['from_email'] : $config->fromEmail;
        $fromName  = !empty($data['from_name']) ? $data['from_name'] : $config->fromName;
        $email->setFrom($fromEmail, $fromName);
        $email->setTo($data['to']);
        $email->setSubject($data['subject']);
        $email->setMessage($data['message']);
        $email->SMTPTimeout = 5;

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
