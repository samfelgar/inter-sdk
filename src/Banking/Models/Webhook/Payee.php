<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Banking\Models\Webhook;

readonly class Payee
{
    public function __construct(
        public string $document,
        public string $name,
    ) {
    }
}
