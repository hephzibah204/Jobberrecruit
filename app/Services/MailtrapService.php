<?php

namespace App\Services;

class MailtrapService
{
    public static function isPermanentResponse(int $status): bool
    {
        return in_array($status, [400, 401, 403, 404, 413, 422], true);
    }
    protected string $apiToken;
    protected string $apiUrl = 'https://send.api.mailtrap.io/api/send';
    protected string $fromEmail;
    protected string $fromName;

    public function __construct()
    {
        $this->apiToken = env('MAILTRAP_API_TOKEN') 
            ?: (env('MAILTRAP_API_KEY') 
            ?: (env('MAILTRAP_TOKEN') 
            ?: env('mailtrap_api_key')));

        $emailConfig = config('Email');
        $this->fromEmail = env('email.fromEmail') 
            ?: (env('email.from_email') 
            ?: ($emailConfig->fromEmail ?: 'info@jobberrecruit.com'));

        $this->fromName = env('email.fromName') 
            ?: (env('email.from_name') 
            ?: ($emailConfig->fromName ?: 'JobberRecruit'));
    }

    /**
     * Send email directly via Mailtrap REST API (HTTPS port 443).
     * Works on all hosting environments without requiring open SMTP ports.
     */
    public function send($to, string $subject, string $html, string $text = '', array $options = []): bool
    {
        if (!is_string($this->apiToken) || trim($this->apiToken) === '') {
            throw new PermanentEmailFailure('Mailtrap API token is not configured.');
        }
        if (empty($to)) {
            log_message('error', 'MailtrapService: Recipient is empty.');
            return false;
        }

        // Format recipients
        $recipients = [];
        if (is_array($to)) {
            foreach ($to as $recipient) {
                if (is_array($recipient) && !empty($recipient['email'])) {
                    $recipients[] = [
                        'email' => trim($recipient['email']),
                        'name'  => $recipient['name'] ?? ''
                    ];
                } elseif (is_string($recipient)) {
                    $recipients[] = ['email' => trim($recipient)];
                }
            }
        } elseif (is_string($to)) {
            // Check for comma-separated emails
            $parts = explode(',', $to);
            foreach ($parts as $part) {
                $email = trim($part);
                if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                    $recipients[] = ['email' => $email];
                }
            }
        }

        if (empty($recipients)) {
            log_message('error', 'MailtrapService: No valid recipients found in ' . json_encode($to));
            return false;
        }

        $suppressions = new EmailSuppressionService();
        $recipients = array_values(array_filter($recipients, static fn ($recipient) =>
            filter_var($recipient['email'], FILTER_VALIDATE_EMAIL) && !$suppressions->isSuppressed($recipient['email'])));
        if (!$recipients) throw new PermanentEmailFailure('All recipients are invalid or suppressed.');

        $fromEmail = !empty($options['from_email']) ? $options['from_email'] : $this->fromEmail;
        $fromName  = !empty($options['from_name']) ? $options['from_name'] : $this->fromName;

        $payload = [
            'to'       => $recipients,
            'from'     => [
                'email' => $fromEmail,
                'name'  => $fromName
            ],
            'subject'  => $subject,
            'html'     => $html,
            'text'     => !empty($text) ? $text : strip_tags($html),
            'category' => $options['category'] ?? 'JobberRecruit Automations'
        ];

        foreach (['cc', 'bcc'] as $field) {
            $payload[$field] = $this->formatRecipients($options[$field] ?? []);
            if (!$payload[$field]) unset($payload[$field]);
        }

        if (!empty($options['reply_to'])) {
            $payload['reply_to'] = [
                'email' => is_array($options['reply_to']) ? ($options['reply_to']['email'] ?? '') : $options['reply_to']
            ];
        }

        if (!empty($options['headers']) && is_array($options['headers'])) {
            $payload['headers'] = $options['headers'];
        }

        try {
            $ch = curl_init($this->apiUrl);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($payload));
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'Authorization: Bearer ' . $this->apiToken,
                'Content-Type: application/json',
                'User-Agent: JobberRecruit-Mailtrap/1.0'
            ]);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_TIMEOUT, 12);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            $curlErr  = curl_error($ch);
            curl_close($ch);

            if ($curlErr) {
                log_message('error', 'MailtrapService cURL Error: ' . $curlErr);
                return false;
            }

            if ($httpCode >= 200 && $httpCode < 300) {
                $body = json_decode($response, true);
                if (!empty($body['success'])) {
                    return true;
                }
            }

            log_message('error', "MailtrapService Delivery Failed (HTTP {$httpCode}): " . $response);
            if (self::isPermanentResponse($httpCode)) {
                throw new PermanentEmailFailure('Mailtrap permanently rejected the request (HTTP ' . $httpCode . '). Check sender, recipient, and account configuration.');
            }
            return false;
        } catch (PermanentEmailFailure $e) {
            throw $e;
        } catch (\Throwable $e) {
            log_message('error', 'MailtrapService Exception: ' . $e->getMessage());
            return false;
        }
    }

    private function formatRecipients($values): array
    {
        $recipients = [];
        foreach ((array) $values as $value) {
            $email = is_array($value) ? trim((string) ($value['email'] ?? '')) : trim((string) $value);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $item = ['email' => $email];
                if (is_array($value) && !empty($value['name'])) $item['name'] = (string) $value['name'];
                $recipients[] = $item;
            }
        }
        return $recipients;
    }
}
