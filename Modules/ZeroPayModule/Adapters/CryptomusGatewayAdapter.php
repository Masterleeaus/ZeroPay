<?php

namespace Modules\ZeroPayModule\Adapters;

use Modules\ZeroPayModule\Contracts\GatewayContract;

class CryptomusGatewayAdapter implements GatewayContract
{
    public function createPayment(array $session): array
    {
        return [
            'status'    => 'pending',
            'gateway'   => 'cryptomus',
            'reference' => uniqid('crypto_', true),
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return ['status' => 'pending', 'reference' => $reference, 'gateway' => 'cryptomus'];
    }

    public function handleWebhook(array $payload): array
    {
        return ['processed' => true, 'gateway' => 'cryptomus', 'payload' => $payload];
    }

    public function calculateFee(float $amount): float
    {
        return round($amount * 0.01, 2);
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'cryptomus'];
    }
}
