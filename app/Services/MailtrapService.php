<?php

namespace App\Services;

class MailtrapService
{
    protected string $apiToken;
    protected string $apiUrl = 'https://send.api.mailtrap.io/api/send';
    protected string $fromEmail;
    protected string $fromName;

    public function __construct()
    {
        $this->apiToken = env('MAILTRAP_API_TOKEN') 
            ?: (env('MAILTRAP_API_KEY') 
            ?: (env('MAILTRAP_TOKEN') 
            ?: (env('mailtrap_api_key') 
            ?: (config('Email')->SMTPPass ?: 'f61d16194a672c3c36fe382d5ebd2f76'))));

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
            return false;
        } catch (\Throwable $e) {
            log_message('error', 'MailtrapService Exception: ' . $e->getMessage());
            return false;
        }
    }
}
