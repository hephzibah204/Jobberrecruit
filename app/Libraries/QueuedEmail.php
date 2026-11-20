<?php

namespace App\Libraries;

use CodeIgniter\Email\Email;

class QueuedEmail extends Email
{
    /**
     * Override standard send method to queue emails when enabled.
     */
    public function send($autoClear = true)
    {
        $suppression = new \App\Services\EmailSuppressionService();
        $allowed = static function ($addresses) use ($suppression) {
            return array_values(array_filter((array) $addresses, static fn ($email) =>
                is_string($email) && filter_var($email, FILTER_VALIDATE_EMAIL) && !$suppression->isSuppressed($email)));
        };
        $to = $allowed($this->recipients);
        $cc = $allowed($this->CCArray);
        $bcc = $allowed($this->BCCArray);
        if (!$to && !$cc && !$bcc) {
            throw new \App\Services\PermanentEmailFailure('No deliverable recipients: invalid or suppressed email address.');
        }
        $this->setTo($to);
        $this->setCC($cc);
        $this->setBCC($bcc);
        $bypassQueue = \Config\Services::$bypassQueue === true;
        $payload = $this->messagePayload();

        // Default to immediate sending so automatic emails are delivered via Mailtrap in real time without depending on external cron.
        $useQueue = env('email_use_queue', env('email.use_queue', false));
        if (!$bypassQueue && ($useQueue === 'true' || $useQueue === true)) {
            $queueModel = new \App\Models\JobQueueModel();

            // Dispatch and run background processor
            $id = $queueModel->dispatchAndRun('transactional_email', $payload);
            if (!$id) return false;

            if ($autoClear) {
                $this->clear();
            }

            return true;
        }

        // Direct real-time sending via SMTP with automatic Mailtrap API failover
        try {
            $sent = $this->sendDirect();
            if (!$sent && count($to) === 1 && !$cc && !$bcc
                && preg_match('/\b5\.1\.1\b/', strip_tags($this->printDebugger(['headers'])))) {
                $suppression->suppress($to[0], 'smtp_invalid_recipient');
                throw new \App\Services\PermanentEmailFailure('SMTP rejected an invalid recipient address.');
            }
        } catch (\App\Services\PermanentEmailFailure $e) {
            throw $e;
        } catch (\Throwable $e) {
            log_message('error', 'SMTP delivery exception: ' . $e->getMessage());
            $sent = false;
        }

        if (!$sent) {
            // SMTP failed or was blocked by host firewall; immediately failover to Mailtrap REST API (HTTPS port 443)
            log_message('warning', 'QueuedEmail: Standard SMTP delivery failed, failing over to Mailtrap REST API.');
            try {
                $sent = $this->sendViaApi();
            } catch (\App\Services\PermanentEmailFailure $e) {
                throw $e;
            } catch (\Throwable $e) {
                log_message('error', 'QueuedEmail Mailtrap API failover exception: ' . $e->getMessage());
            }
        }

        if (!$sent && !$bypassQueue) {
            // Keep failed transactional messages for the existing queue worker to retry.
            try {
                $sent = (bool) (new \App\Models\JobQueueModel())->dispatch('transactional_email', $payload);
                if ($sent) log_message('warning', 'Transactional email saved for retry after delivery failed.');
            } catch (\Throwable $e) {
                log_message('error', 'Could not retain failed email for retry: ' . $e->getMessage());
            }
        }
        if ($sent && $autoClear) $this->clear();
        return $sent;
    }

    protected function sendDirect(): bool
    {
        return parent::send(false);
    }

    protected function sendViaApi(): bool
    {
        $payload = $this->messagePayload();
        return (new \App\Services\MailtrapService())->send(
            $payload['to'], $payload['subject'], $payload['message'], $payload['alt_message'], $payload
        );
    }

    protected function messagePayload(): array
    {
        // CodeIgniter stores setSubject()/setFrom() values in tmpArchive. The
        // subject property is empty for SMTP and is not the original subject.
        return [
            'to' => $this->recipients,
            'cc' => $this->CCArray,
            'bcc' => $this->BCCArray,
            'from_email' => $this->tmpArchive['fromEmail'] ?? $this->fromEmail,
            'from_name' => $this->tmpArchive['fromName'] ?? $this->fromName,
            'reply_to' => $this->tmpArchive['replyTo'] ?? null,
            'subject' => $this->tmpArchive['subject'] ?? $this->subject,
            'message' => $this->body,
            'alt_message' => $this->altMessage,
            'mail_type' => $this->mailType,
            'headers' => array_filter($this->headers, static fn ($name) => str_starts_with($name, 'X-') || $name === 'List-Unsubscribe', ARRAY_FILTER_USE_KEY),
        ];
    }
}
