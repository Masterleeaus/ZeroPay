<?php

namespace Modules\ZeroPayModule\ValueObjects;

use Modules\ZeroPayModule\Models\ZeroPaySession;

/**
 * Result returned by the bank transfer matcher.
 *
 * - status: auto_matched|needs_review
 * - session is null when no candidate session was identified
 * - matchedCriteria includes boolean keys: reference, amount, customer, timestamp
 */
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
