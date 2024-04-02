<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Responses;

use Samfelgar\Inter\PixCharges\Models\Situation;

readonly class SummaryItem
{
    public function __construct(
        public Situation $situation,
        public float $amount,
        public int $quantity,
    ) {
    }

    public static function fromArray(array $data): SummaryItem
    {
        return new SummaryItem(
            Situation::from($data['situacao']),
            (float) $data['valor'],
            (int) $data['quantidade'],
        );
    }
}
