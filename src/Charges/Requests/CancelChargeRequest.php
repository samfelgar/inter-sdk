<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Requests;

readonly class CancelChargeRequest implements \JsonSerializable
{
    public function __construct(
        public string $ourNumber,
        public CancelReason $reason,
    ) {
    }

    /**
     * @return array<string, string>
     */
    public function jsonSerialize(): array
    {
        return [
            'motivoCancelamento' => $this->reason->value,
        ];
    }
}
