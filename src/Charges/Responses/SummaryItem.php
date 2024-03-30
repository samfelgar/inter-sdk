<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Responses;

readonly class SummaryItem
{
    public function __construct(
        public int $quantity,
        public float $amount,
    ) {
    }
}
