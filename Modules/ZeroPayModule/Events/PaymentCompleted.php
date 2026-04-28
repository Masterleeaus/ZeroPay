<?php

namespace Modules\ZeroPayModule\Events;

use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class PaymentCompleted
{
    use Dispatchable;
    use SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param string               $reference
     * @param array<string, mixed> $paymentData
     */
    public function __construct(
        public readonly string $reference,
        public readonly array $paymentData = []
    ) {}
}
