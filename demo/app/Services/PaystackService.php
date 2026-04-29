<?php

namespace App\Services;

class PaystackService
{
    protected $secretKey;
    protected $baseUrl = 'https://api.paystack.co';

    public function __construct()
    {
        $this->secretKey = env('paystack_secret_key');
    }

    /**
     * Initialize transaction
     */
    public function initialize($email, $amount, $callbackUrl, $metadata = [])
    {
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
        $url = $this->baseUrl . '/transaction/verify/' . rawurlencode($reference);
        return $this->makeRequest($url, [], 'GET');
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
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
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
