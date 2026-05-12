<?php

namespace Modules\ZeroPayModule\Adapters;

use Modules\ZeroPayModule\Contracts\GatewayContract;
use Modules\ZeroPayModule\Services\Gateways\BankTransferGateway;

class BankTransferGatewayAdapter implements GatewayContract
{
    public function __construct(
        protected BankTransferGateway $gateway,
    ) {}

    public function createPayment(array $session): array
    {
        return $this->gateway->createPayment($session);
    }

    public function verifyPayment(string $reference): array
    {
        return $this->gateway->verifyPayment($reference);
    }

    public function handleWebhook(array $payload): array
    {
        return $this->gateway->handleWebhook($payload);
    }

    public function calculateFee(float $amount): float
    {
        return $this->gateway->calculateFee($amount);
    }

    public function refundPayment(string $transactionId): array
    {
        return $this->gateway->refundPayment($transactionId);
    }
}
