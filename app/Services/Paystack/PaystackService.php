<?php

namespace App\Services\Paystack;

use GuzzleHttp\Client;
use RuntimeException;

class PaystackService
{
    private $client;

    public function __construct()
    {
        $secretKey = config('services.paystack.secret_key');
        if (!is_string($secretKey) || $secretKey === '') {
            throw new RuntimeException('Paystack secret key is not configured.');
        }

        $this->client = new Client([
            'base_uri' => rtrim(config('services.paystack.base_url'), '/') . '/',
            'headers' => [
                'Authorization' => 'Bearer ' . $secretKey,
                'Accept' => 'application/json',
            ],
            'connect_timeout' => 5,
            'timeout' => 15,
        ]);
    }

    public function initialize(string $email, float $amount, string $reference, string $callbackUrl): array
    {
        $payment = $this->request('POST', 'transaction/initialize', [
            'email' => $email,
            'amount' => (int) round($amount * 100),
            'reference' => $reference,
            'callback_url' => $callbackUrl,
            'currency' => 'NGN',
        ]);

        if (empty($payment['authorization_url']) || empty($payment['reference'])) {
            throw new RuntimeException('Paystack returned an incomplete initialization response.');
        }

        return $payment;
    }

    public function verify(string $reference): array
    {
        return $this->request('GET', 'transaction/verify/' . rawurlencode($reference));
    }

    private function request(string $method, string $uri, array $payload = []): array
    {
        $options = $method === 'GET' ? [] : ['json' => $payload];
        $response = $this->client->request($method, $uri, $options);
        $body = json_decode((string) $response->getBody(), true);

        if (!is_array($body) || !array_key_exists('status', $body)) {
            throw new RuntimeException('Paystack returned an invalid response.');
        }

        if ($body['status'] !== true) {
            throw new RuntimeException($body['message'] ?? 'Paystack request failed.');
        }

        if (!isset($body['data']) || !is_array($body['data'])) {
            throw new RuntimeException('Paystack returned an invalid response.');
        }

        return $body['data'];
    }
}