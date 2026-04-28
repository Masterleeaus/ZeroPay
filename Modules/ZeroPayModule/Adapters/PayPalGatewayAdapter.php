<?php

namespace Modules\ZeroPayModule\Adapters;

use Modules\ZeroPayModule\Contracts\GatewayContract;

class PayPalGatewayAdapter implements GatewayContract
{
    public function createPayment(array $session): array
    {
        return [
            'status'    => 'pending',
            'gateway'   => 'paypal',
            'reference' => uniqid('pp_', true),
        ];
    }

    public function verifyPayment(string $reference): array
    {
        return ['status' => 'pending', 'reference' => $reference, 'gateway' => 'paypal'];
    }

    public function handleWebhook(array $payload): array
    {
        return ['processed' => true, 'gateway' => 'paypal', 'payload' => $payload];
    }

    public function calculateFee(float $amount): float
    {
        return round($amount * 0.034 + 0.49, 2);
    }

    public function refundPayment(string $transactionId): array
    {
        return ['status' => 'refunded', 'transaction_id' => $transactionId, 'gateway' => 'paypal'];
    }
}
