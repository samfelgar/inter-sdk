<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Models;

readonly class Slip
{
    public function __construct(
        public string $ourNumber,
        public string $barCode,
        public string $line,
    ) {
    }

    public static function fromArray(array $data): Slip
    {
        return new Slip(
            $data['nossoNumero'],
            $data['codigoBarras'],
            $data['linhaDigitavel'],
        );
    }
}
