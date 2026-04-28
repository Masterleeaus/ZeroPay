<?php

namespace Modules\ZeroPayModule\Contracts;

interface GatewayContract
{
    public function createPayment(array $session): array;
    public function verifyPayment(string $reference): array;
    public function handleWebhook(array $payload): array;
    public function calculateFee(float $amount): float;
    public function refundPayment(string $transactionId): array;
}
