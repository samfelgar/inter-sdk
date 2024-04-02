<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Models;

class Moratorium
{
    public function __construct(
        public MoratoriumCode $code,
        private ?float $tax,
        private ?float $amount,
    ) {
        if ($this->code === MoratoriumCode::MonthlyTax && ($this->tax === null || $this->tax === 0.0)) {
            throw new \InvalidArgumentException('Tax required for selected code');
        }

        if ($this->code === MoratoriumCode::DailyAmount && ($this->amount === null || $this->amount === 0.0)) {
            throw new \InvalidArgumentException('Amount required for selected code');
        }
    }

    public function getTax(): ?float
    {
        if ($this->code !== MoratoriumCode::MonthlyTax || $this->isNoMoratorium()) {
            return 0;
        }
        return $this->tax;
    }

    public function getAmount(): ?float
    {
        if ($this->code !== MoratoriumCode::DailyAmount || $this->isNoMoratorium()) {
            return 0;
        }
        return $this->amount;
    }

    private function isNoMoratorium(): bool
    {
        return $this->code === MoratoriumCode::NoMoratorium;
    }
}
