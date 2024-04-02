<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Models;

use Webmozart\Assert\Assert;

class Recipient
{
    public readonly string $cpfCnpj;
    public readonly string $zipCode;
    public readonly string $state;

    public function __construct(
        public readonly string $name,
        string $cpfCnpj,
        public readonly PersonType $type,
        string $zipCode,
        public readonly string $address,
        public readonly string $neighborhood,
        public readonly string $city,
        string $state,
    ) {
        $cpfCnpj = \preg_replace('/\D/', '', $cpfCnpj);
        $zipCode = \preg_replace('/\D/', '', $zipCode);

        if (!(\strlen($cpfCnpj) === 11 || \strlen($cpfCnpj) === 14)) {
            throw new \InvalidArgumentException('The CPF/CNPJ must have 11 or 14 characters');
        }

        Assert::length($zipCode, 8);
        Assert::length($state, 2);

        $this->cpfCnpj = $cpfCnpj;
        $this->zipCode = $zipCode;
        $this->state = \mb_convert_case($state, MB_CASE_UPPER);
    }
}
