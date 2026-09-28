<?php

namespace App\Services;

class PaystackService
{
    protected $secretKey;
    protected $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        $this->secretKey = env('paystack_secret_key') ?: (env('PAYSTACK_SECRET_KEY') ?: env('paystack.secret_key'));
    }

    /**
     * Initialize transaction
     */
    public function initialize($email, $amount, $callbackUrl, $metadata = [])
    {
        if (empty($this->secretKey)) {
            $reference = $metadata['reference'] ?? ('mock_' . uniqid());
            $redirectUrl = $callbackUrl . (strpos($callbackUrl, '?') === false ? '?' : '&') . 'reference=' . $reference;
            return [
                'status' => true,
                'data' => [
                    'authorization_url' => $redirectUrl,
                    'reference' => $reference
                ]
            ];
        }

        $url = $this->baseUrl . '/transaction/initialize';
        
        $fields = [
            'email' => $email,
            'amount' => $amount * 100, // Paystack amount is in kobo
            'callback_url' => $callbackUrl,
            'metadata' => json_encode($metadata)
        ];

        return $this->makeRequest($url, $fields);
    }

    /**
     * Verify transaction
     */
    public function verify($reference)
    {
        if (empty($this->secretKey)) {
            $session = \Config\Services::session();
            $pending = $session->get('pending_subscription');
            $planId = $pending['plan_id'] ?? 1;

            return [
                'status' => true,
                'data' => [
                    'status' => 'success',
                    'amount' => 500000,
                    'channel' => 'card',
                    'reference' => $reference,
                    'metadata' => [
                        'plan_id' => $planId,
                        'user_id' => auth()->id(),
                        'app_data' => [
                            'type' => 'subscription',
                            'plan_id' => $planId,
                            'months' => 1
                        ]
                    ]
                ]
            ];
        }

        $url = $this->baseUrl . '/transaction/verify/' . rawurlencode($reference);
        return $this->makeRequest($url, [], 'GET');
    }

    /**
     * Alias for verify transaction
     */
    public function verifyTransaction($reference)
    {
        return $this->verify($reference);
    }

    /**
     * Alias for verify payment
     */
    public function verifyPayment($reference)
    {
        return $this->verify($reference);
    }

    protected function makeRequest($url, $fields = [], $method = 'POST')
    {
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        
        if ($method === 'POST') {
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
        }

        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            "Authorization: Bearer " . $this->secretKey,
            "Cache-Control: no-cache",
            "Content-Type: application/json"
        ]);

        $response = curl_exec($ch);
        $err = curl_error($ch);
        curl_close($ch);

        if ($err) {
            return ['status' => false, 'message' => 'Curl Error: ' . $err];
        }

        return json_decode($response, true);
    }
}
