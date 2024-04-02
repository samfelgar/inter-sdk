<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Models;

use Webmozart\Assert\Assert;

readonly class Payer
{
    public string $cpfCnpj;
    public string $state;
    public string $zipCode;

    public function __construct(
        string $cpfCnpj,
        public PersonType $type,
        public string $name,
        public string $address,
        public ?string $number,
        public ?string $complement,
        public ?string $neighborhood,
        public string $city,
        string $state,
        string $zipCode,
        public ?string $email,
        public ?Phone $phone,
    ) {
        $cpfCnpj = \preg_replace('/\D/', '', $cpfCnpj);
        if (!(\strlen($cpfCnpj) === 11 || \strlen($cpfCnpj) === 14)) {
            throw new \InvalidArgumentException('The CPF/CNPJ must have 11 or 14 characters');
        }

        $this->cpfCnpj = $cpfCnpj;
        Assert::lengthBetween($this->name, 1, 100);
        Assert::lengthBetween($this->address, 1, 100);

        if ($this->number !== null) {
            Assert::maxLength($this->number, 10);
        }
        if ($this->complement !== null) {
            Assert::maxLength($this->complement, 30);
        }
        if ($this->neighborhood !== null) {
            Assert::maxLength($this->neighborhood, 60);
        }
        Assert::lengthBetween($this->city, 1, 60);
        Assert::length($state, 2);
        $zipCode = \preg_replace('/\D/', '', $zipCode);
        Assert::length($zipCode, 8);
        if ($this->email !== null) {
            Assert::maxLength($this->email, 50);
            Assert::email($this->email);
        }

        $this->state = \mb_convert_case($state, MB_CASE_UPPER);
        $this->zipCode = $zipCode;
    }
}
