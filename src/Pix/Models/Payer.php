<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Pix\Models;

use Webmozart\Assert\Assert;

class Payer
{
    public function __construct(
        public readonly string $cpfCnpj,
        public readonly string $name,
    ) {
        $cpfCnpj = \preg_replace('/\D/', '', $cpfCnpj);
        if (!(\strlen($cpfCnpj) === 11 || \strlen($cpfCnpj) === 14)) {
            throw new \InvalidArgumentException('The CPF/CNPJ must have 11 or 14 characters');
        }
        Assert::maxLength($this->name, 200);
    }

    public function hasCpf(): bool
    {
        return \strlen($this->cpfCnpj) === 11;
    }
}
