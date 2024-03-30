<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Tests\Charges\Models;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Inter\Charges\Models\Discount;
use Samfelgar\Inter\Charges\Models\DiscountCode;

class DiscountTest extends TestCase
{
    #[Test]
    public function itCanCreateADiscount(): void
    {
        $discount = new Discount(
            DiscountCode::FixedValue,
            new \DateTimeImmutable(),
            0,
            20
        );
        $this->assertInstanceOf(Discount::class, $discount);
    }

    #[Test]
    public function itRequiresADateForFixedValueDiscount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Discount(
            DiscountCode::FixedValue,
            null,
            0,
            20
        );
    }

    #[Test]
    public function itRequiresADateForPercentageValueDiscount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Discount(
            DiscountCode::PercentageValue,
            null,
            0,
            20
        );
    }

    #[Test]
    public function itRequiresATaxForPercentageValueDiscount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Discount(
            DiscountCode::PercentageValue,
            new \DateTimeImmutable(),
            0,
            null
        );
    }

    #[Test]
    public function itRequiresAAmountForFixedValueDiscount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Discount(
            DiscountCode::FixedValue,
            new \DateTimeImmutable(),
            null,
            0
        );
    }

    #[Test]
    public function itCanGetTheDiscountDate(): void
    {
        $discount = new Discount(
            DiscountCode::FixedValue,
            new \DateTimeImmutable(),
            null,
            20
        );
        $this->assertInstanceOf(\DateTimeImmutable::class, $discount->getDate());
    }

    #[Test]
    public function itGetANullDateIfIsANoDiscount(): void
    {
        $discount = new Discount(
            DiscountCode::NoDiscount,
            new \DateTimeImmutable(),
            null,
            null
        );
        $this->assertNull($discount->getDate());
    }

    #[Test]
    public function itCanGetTheDiscountTax(): void
    {
        $discount = new Discount(
            DiscountCode::PercentageValue,
            new \DateTimeImmutable(),
            20,
            null
        );
        $this->assertEquals(20, $discount->getTax());
    }

    #[Test]
    public function itGetZeroTaxForDiscountsThatArentPercentage(): void
    {
        $discount = new Discount(
            DiscountCode::FixedValue,
            new \DateTimeImmutable(),
            20,
            20
        );
        $this->assertEquals(0.0, $discount->getTax());
    }

    #[Test]
    public function itCanGetTheDiscountAmount(): void
    {
        $discount = new Discount(
            DiscountCode::FixedValue,
            new \DateTimeImmutable(),
            null,
            20
        );
        $this->assertEquals(20, $discount->getAmount());
    }

    #[Test]
    public function itGetZeroAmountForDiscountsThatArentFixedValue(): void
    {
        $discount = new Discount(
            DiscountCode::PercentageValue,
            new \DateTimeImmutable(),
            20,
            20
        );
        $this->assertEquals(0.0, $discount->getAmount());
    }
}
