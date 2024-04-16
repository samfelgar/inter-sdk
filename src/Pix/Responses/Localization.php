<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Pix\Responses;

use Samfelgar\Inter\Pix\Models\ChargeType;

readonly class Localization
{
    public function __construct(
        public int $id,
        public ?string $location,
        public ChargeType $type,
        public \DateTimeImmutable $createdAt,
    ) {
    }

    public static function fromArray(array $data): Localization
    {
        return new Localization(
            $data['id'],
            $data['location'] ?? null,
            ChargeType::from($data['tipoCob']),
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, $data['criacao']),
        );
    }
}
