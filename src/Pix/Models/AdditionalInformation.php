<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Pix\Models;

readonly class AdditionalInformation
{
    public function __construct(
        public string $name,
        public string $value,
    ) {
    }
}
