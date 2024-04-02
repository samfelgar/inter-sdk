<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Models;

readonly class Discount
{
    public function __construct(
        public DiscountCode $code,
        public int $daysBeforeDueDate,
        private ?float $tax,
        private ?float $amount,
    ) {
        if ($this->code === DiscountCode::PercentageValue && ($this->tax === null || $this->tax === 0.0)) {
            throw new \InvalidArgumentException('Tax required for selected code');
        }

        if ($this->code === DiscountCode::FixedValue && ($this->amount === null || $this->amount === 0.0)) {
            throw new \InvalidArgumentException('Amount required for selected code');
        }
    }

    public static function noDiscount(): Discount
    {
        return new Discount(DiscountCode::NoDiscount, 0, 0, 0);
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
