<?php

namespace App\Services;

use App\Models\JobQueueModel;

class EmailQueueWorker
{
    public function __construct(private ?JobQueueModel $queue = null)
    {
    }

    public function process(int $limit = 50, ?array $types = null): array
    {
        $queue = $this->queue ?? model(JobQueueModel::class);
        $result = ['jobs_processed' => 0, 'jobs_failed' => 0];
        for ($i = 0; $i < $limit && ($job = $queue->claimNext($types)); $i++) {
            $payload = json_decode($job->payload, true) ?: [];
            $data = $payload['data'] ?? [];
            $success = false;
            $error = null;
            $permanent = false;
            try {
                $success = $this->deliver($payload['type'] ?? '', $data);
                if (!$success) {
                    $error = 'Email delivery failed.';
                }
            } catch (PermanentEmailFailure $e) {
                $error = $e->getMessage();
                $permanent = true;
            } catch (\Throwable $e) {
                $error = $e->getMessage();
                log_message('error', 'Queue delivery failed: ' . $error);
            }
            if ($queue->finishClaim($job, $success, $error, $permanent)) {
                $result[$success ? 'jobs_processed' : 'jobs_failed']++;
                // Delivery succeeded independently of whether log bookkeeping does.
                if (!empty($data['log_id']) && ($success || $permanent || (int) $job->attempts >= 3)) {
                    try {
                        model(\App\Models\EmailLogModel::class)->update($data['log_id'], $success
                            ? ['delivered_at' => date('Y-m-d H:i:s')]
                            : ['bounce_reason' => $error]);
                    } catch (\Throwable $e) {
                        log_message('error', 'Queue log update failed: ' . $e->getMessage());
                    }
                }
            }
        }
        return $result;
    }

    protected function deliver(string $type, array $data): bool
    {
        if (!in_array($type, ['transactional_email', 'newsletter_email', 'campaign_email'], true)) {
            throw new \InvalidArgumentException('Unknown email job type.');
        }
        $config = config('Email');
        $email = new \App\Libraries\QueuedEmail($config);
        $email->SMTPTimeout = 15;
        $email->setFrom($data['from_email'] ?? $config->fromEmail, $data['from_name'] ?? $config->fromName);
        $email->setTo($type === 'transactional_email' ? $data['to'] : $data['email']);
        $email->setSubject($data['subject']);
        $email->setMessage($type === 'transactional_email' ? $data['message'] : $data['content']);
        $email->setMailType($data['mail_type'] ?? 'html');
        if (!empty($data['alt_message'])) {
            $email->setAltMessage($data['alt_message']);
        }
        if (!empty($data['reply_to'])) {
            $email->setReplyTo($data['reply_to']);
        }
        foreach ($data['headers'] ?? [] as $name => $value) {
            $email->setHeader($name, $value);
        }
        $previousBypass = \Config\Services::$bypassQueue;
        \Config\Services::$bypassQueue = true;
        try {
            return $email->send();
        } finally {
            \Config\Services::$bypassQueue = $previousBypass;
        }
    }
}
