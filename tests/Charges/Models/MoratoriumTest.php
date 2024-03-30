<?php

namespace Samfelgar\Inter\Tests\Charges\Models;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Inter\Charges\Models\Moratorium;
use Samfelgar\Inter\Charges\Models\MoratoriumCode;

class MoratoriumTest extends TestCase
{
    #[Test]
    public function itCanCreateAMoratorium(): void
    {
        $interest = new Moratorium(
            MoratoriumCode::DailyAmount,
            new \DateTimeImmutable(),
            0,
            20
        );
        $this->assertInstanceOf(Moratorium::class, $interest);
    }

    #[Test]
    public function itRequiresADateForDailyAmountMoratorium(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Moratorium(
            MoratoriumCode::DailyAmount,
            null,
            0,
            20
        );
    }

    #[Test]
    public function itRequiresADateForMonthlyTaxMoratorium(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Moratorium(
            MoratoriumCode::MonthlyTax,
            null,
            0,
            20
        );
    }

    #[Test]
    public function itRequiresATaxForMonthlyTaxMoratorium(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Moratorium(
            MoratoriumCode::MonthlyTax,
            new \DateTimeImmutable(),
            0,
            null
        );
    }

    #[Test]
    public function itRequiresAAmountForDailyAmountMoratorium(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Moratorium(
            MoratoriumCode::DailyAmount,
            new \DateTimeImmutable(),
            null,
            0
        );
    }

    #[Test]
    public function itCanGetTheMoratoriumDate(): void
    {
        $interest = new Moratorium(
            MoratoriumCode::DailyAmount,
            new \DateTimeImmutable(),
            null,
            20
        );
        $this->assertInstanceOf(\DateTimeImmutable::class, $interest->getDate());
    }

    #[Test]
    public function itGetANullDateIfIsANoMoratorium(): void
    {
        $interest = new Moratorium(
            MoratoriumCode::NoMoratorium,
            new \DateTimeImmutable(),
            null,
            null
        );
        $this->assertNull($interest->getDate());
    }

    #[Test]
    public function itCanGetTheMoratoriumTax(): void
    {
        $interest = new Moratorium(
            MoratoriumCode::MonthlyTax,
            new \DateTimeImmutable(),
            20,
            null
        );
        $this->assertEquals(20, $interest->getTax());
    }

    #[Test]
    public function itGetZeroTaxForMoratoriumsThatArentPercentage(): void
    {
        $interest = new Moratorium(
            MoratoriumCode::DailyAmount,
            new \DateTimeImmutable(),
            20,
            20
        );
        $this->assertEquals(0.0, $interest->getTax());
    }

    #[Test]
    public function itCanGetTheMoratoriumAmount(): void
    {
        $interest = new Moratorium(
            MoratoriumCode::DailyAmount,
            new \DateTimeImmutable(),
            null,
            20
        );
        $this->assertEquals(20, $interest->getAmount());
    }

    #[Test]
    public function itGetZeroAmountForMoratoriumsThatArentDailyAmount(): void
    {
        $interest = new Moratorium(
            MoratoriumCode::MonthlyTax,
            new \DateTimeImmutable(),
            20,
            20
        );
        $this->assertEquals(0.0, $interest->getAmount());
    }
}
