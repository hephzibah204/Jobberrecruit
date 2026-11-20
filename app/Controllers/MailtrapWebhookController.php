<?php
namespace App\Controllers;

use App\Services\EmailSuppressionService;

class MailtrapWebhookController extends BaseController
{
    public function receive()
    {
        $body = $this->request->getBody();
        if (!EmailSuppressionService::authentic($body, $this->request->getHeaderLine('Mailtrap-Signature'), (string) env('MAILTRAP_WEBHOOK_SECRET', ''))) {
            return $this->response->setStatusCode(401)->setJSON(['error' => 'Invalid webhook signature.']);
        }
        if (strlen($body) > 2097152) return $this->response->setStatusCode(413);
        $payload = json_decode($body, true);
        $events = $payload['events'] ?? $payload;
        if (!is_array($events) || !array_is_list($events) || count($events) > 500) {
            return $this->response->setStatusCode(400)->setJSON(['error' => 'Use JSON webhook batches.']);
        }
        try {
            $suppressions = new EmailSuppressionService();
            foreach ($events as $event) {
                if (!is_array($event)) return $this->response->setStatusCode(400);
                $suppressions->record($event);
            }
            return $this->response->setJSON(['success' => true]);
        } catch (\Throwable $e) {
            log_message('error', 'Mailtrap suppression webhook could not be saved.');
            return $this->response->setStatusCode(500);
        }
    }
}
