<?php

namespace Modules\ZeroPayModule\Adapters;

use Modules\ZeroPayModule\Contracts\GatewayContract;

class DefaultGatewayAdapter implements GatewayContract
{
    /**
     * Initiate a payment session.
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function initiate(array $payload): array
    {
        return [
            'status'    => 'pending',
            'reference' => uniqid('zpay_', true),
            'payload'   => $payload,
        ];
    }

    /**
     * Verify the status of a payment.
     *
     * @param string $reference
     * @return array<string, mixed>
     */
    public function verify(string $reference): array
    {
        return [
            'status'    => 'pending',
            'reference' => $reference,
        ];
    }

    /**
     * Refund a completed payment.
     *
     * @param string $reference
     * @param float  $amount
     * @return array<string, mixed>
     */
    public function refund(string $reference, float $amount): array
    {
        return [
            'status'    => 'refunded',
            'reference' => $reference,
            'amount'    => $amount,
        ];
    }
}
