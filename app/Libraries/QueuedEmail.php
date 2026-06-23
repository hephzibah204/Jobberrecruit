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

        // If queueing is enabled, serialize the mail and push it to the queue
        $useQueue = env('email_use_queue', env('email.use_queue', 'true'));
        if ($useQueue === 'true' || $useQueue === true) {
            $queueModel = new \App\Models\JobQueueModel();

            // Extract recipients
            $to = is_array($this->recipients) ? implode(', ', $this->recipients) : $this->recipients;

            $payload = [
                'to'          => $to,
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

        // Fall back to synchronous sending
        return parent::send($autoClear);
    }
}
