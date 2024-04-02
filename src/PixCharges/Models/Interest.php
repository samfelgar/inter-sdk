<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Models;

class Interest
{
    public function __construct(
        public InterestCode $code,
        private ?float $tax,
        private ?float $amount,
    ) {
        if ($this->code === InterestCode::PercentualAmount && ($this->tax === null || $this->tax === 0.0)) {
            throw new \InvalidArgumentException('Tax required for selected code');
        }

        if ($this->code === InterestCode::FixedAmount && ($this->amount === null || $this->amount === 0.0)) {
            throw new \InvalidArgumentException('Amount required for selected code');
        }
    }

    public function getTax(): ?float
    {
        if ($this->code !== InterestCode::PercentualAmount || $this->isNoInterest()) {
            return 0;
        }
        return $this->tax;
    }

    public function getAmount(): ?float
    {
        if ($this->code !== InterestCode::FixedAmount || $this->isNoInterest()) {
            return 0;
        }
        return $this->amount;
    }

    private function isNoInterest(): bool
    {
        return $this->code === InterestCode::NoInterest;
    }
}
