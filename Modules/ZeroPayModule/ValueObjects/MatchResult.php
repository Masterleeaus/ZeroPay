<?php

namespace Modules\ZeroPayModule\ValueObjects;

use Modules\ZeroPayModule\Models\ZeroPaySession;

class MatchResult
{
    public function __construct(
        public bool $matched,
        public ?ZeroPaySession $session,
        public float $confidence,
        public array $matchedCriteria,
        public string $status,
    ) {}
}
