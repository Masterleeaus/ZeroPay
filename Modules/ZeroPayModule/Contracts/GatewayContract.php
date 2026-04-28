<?php

namespace Modules\ZeroPayModule\Contracts;

interface GatewayContract
{
    /**
     * Initiate a payment session.
     *
     * @param array<string, mixed> $payload
     * @return array<string, mixed>
     */
    public function initiate(array $payload): array;

    /**
     * Verify the status of a payment.
     *
     * @param string $reference
     * @return array<string, mixed>
     */
    public function verify(string $reference): array;

    /**
     * Refund a completed payment.
     *
     * @param string $reference
     * @param float  $amount
     * @return array<string, mixed>
     */
    public function refund(string $reference, float $amount): array;
}
