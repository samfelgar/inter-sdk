<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Models;

readonly class Pix
{
    public function __construct(
        public string $id,
        public string $copyAndPasteCode,
    ) {
    }

    public static function fromArray(array $data): Pix
    {
        return new Pix(
            $data['txid'],
            $data['pixCopiaECola'],
        );
    }
}
