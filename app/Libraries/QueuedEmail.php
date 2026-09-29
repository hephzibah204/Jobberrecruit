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
        if (isset(\Config\Services::$bypassQueue) && \Config\Services::$bypassQueue === true) {
            return parent::send($autoClear);
        }

        // Default to immediate sending so automatic emails are delivered via Mailtrap in real time without depending on external cron.
        $useQueue = env('email_use_queue', env('email.use_queue', false));
        if ($useQueue === 'true' || $useQueue === true) {
            $queueModel = new \App\Models\JobQueueModel();

            // Extract recipients
            $to = is_array($this->recipients) ? implode(', ', $this->recipients) : $this->recipients;

            $payload = [
                'to'          => $to,
                'from_email'  => $this->fromEmail ?? null,
                'from_name'   => $this->fromName ?? null,
                'reply_to'    => $this->replyTo ?? null,
                'subject'     => $this->subject,
                'message'     => $this->body,
                'alt_message' => $this->altMessage,
                'mail_type'   => $this->mailType,
                'headers'     => $this->headers,
            ];

            // Dispatch and run background processor
            $queueModel->dispatchAndRun('transactional_email', $payload);

            if ($autoClear) {
                $this->clear();
            }

            return true;
        }

        // Direct real-time sending via SMTP with automatic Mailtrap API failover
        $sent = parent::send($autoClear);

        if (!$sent) {
            // SMTP failed or was blocked by host firewall; immediately failover to Mailtrap REST API (HTTPS port 443)
            log_message('warning', 'QueuedEmail: Standard SMTP delivery failed, failing over to Mailtrap REST API.');
            try {
                $mailtrap = new \App\Services\MailtrapService();
                $sent = $mailtrap->send(
                    $this->recipients,
                    $this->subject,
                    $this->body,
                    $this->altMessage,
                    [
                        'from_email' => $this->fromEmail,
                        'from_name'  => $this->fromName,
                        'reply_to'   => $this->replyTo,
                        'headers'    => $this->headers,
                    ]
                );

                if ($sent && $autoClear) {
                    $this->clear();
                }
            } catch (\Throwable $e) {
                log_message('error', 'QueuedEmail Mailtrap API failover exception: ' . $e->getMessage());
            }
        }

        return $sent;
    }
}
