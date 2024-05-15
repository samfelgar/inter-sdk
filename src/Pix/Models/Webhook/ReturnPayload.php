<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Pix\Models\Webhook;

readonly class ReturnPayload
{
    public function __construct(
        public string $id,
        public string $returnId,
        public float $amount,
        public \DateTimeImmutable $requestedAt,
        public \DateTimeImmutable $settledAt,
        public ReturnStatus $status,
        public ?string $reason,
    ) {
    }
}
