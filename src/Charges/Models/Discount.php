<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Models;

readonly class Discount
{
    public function __construct(
        public DiscountCode $code,
        private ?\DateTimeImmutable $date,
        private ?float $tax,
        private ?float $amount,
    ) {
        if (\in_array($this->code, [DiscountCode::FixedValue, DiscountCode::PercentageValue]) && $this->date === null) {
            throw new \InvalidArgumentException('Date required for selected code');
        }

        if ($this->code === DiscountCode::PercentageValue && ($this->tax === null || $this->tax === 0.0)) {
            throw new \InvalidArgumentException('Tax required for selected code');
        }

        if ($this->code === DiscountCode::FixedValue && ($this->amount === null || $this->amount === 0.0)) {
            throw new \InvalidArgumentException('Amount required for selected code');
        }
    }

    public function getDate(): ?\DateTimeImmutable
    {
        if (!\in_array($this->code, [DiscountCode::FixedValue, DiscountCode::PercentageValue]) || $this->isNoDiscount()) {
            return null;
        }
        return $this->date;
    }

    public function getTax(): ?float
    {
        if ($this->code !== DiscountCode::PercentageValue || $this->isNoDiscount()) {
            return 0;
        }
        return $this->tax;
    }

    public function getAmount(): ?float
    {
        if ($this->code !== DiscountCode::FixedValue || $this->isNoDiscount()) {
            return 0;
        }
        return $this->amount;
    }

    private function isNoDiscount(): bool
    {
        return $this->code === DiscountCode::NoDiscount;
    }
}
