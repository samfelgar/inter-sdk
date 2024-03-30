<?php

namespace Samfelgar\Inter\Tests\Charges\Models;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Inter\Charges\Models\Interest;
use Samfelgar\Inter\Charges\Models\InterestCode;

class InterestTest extends TestCase
{
    #[Test]
    public function itCanCreateAInterest(): void
    {
        $interest = new Interest(
            InterestCode::FixedAmount,
            new \DateTimeImmutable(),
            0,
            20
        );
        $this->assertInstanceOf(Interest::class, $interest);
    }

    #[Test]
    public function itRequiresADateForFixedAmountInterest(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Interest(
            InterestCode::FixedAmount,
            null,
            0,
            20
        );
    }

    #[Test]
    public function itRequiresADateForPercentualAmountInterest(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Interest(
            InterestCode::PercentualAmount,
            null,
            0,
            20
        );
    }

    #[Test]
    public function itRequiresATaxForPercentualAmountInterest(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Interest(
            InterestCode::PercentualAmount,
            new \DateTimeImmutable(),
            0,
            null
        );
    }

    #[Test]
    public function itRequiresAAmountForFixedAmountInterest(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Interest(
            InterestCode::FixedAmount,
            new \DateTimeImmutable(),
            null,
            0
        );
    }

    #[Test]
    public function itCanGetTheInterestDate(): void
    {
        $interest = new Interest(
            InterestCode::FixedAmount,
            new \DateTimeImmutable(),
            null,
            20
        );
        $this->assertInstanceOf(\DateTimeImmutable::class, $interest->getDate());
    }

    #[Test]
    public function itGetANullDateIfIsANoInterest(): void
    {
        $interest = new Interest(
            InterestCode::NoInterest,
            new \DateTimeImmutable(),
            null,
            null
        );
        $this->assertNull($interest->getDate());
    }

    #[Test]
    public function itCanGetTheInterestTax(): void
    {
        $interest = new Interest(
            InterestCode::PercentualAmount,
            new \DateTimeImmutable(),
            20,
            null
        );
        $this->assertEquals(20, $interest->getTax());
    }

    #[Test]
    public function itGetZeroTaxForInterestsThatArentPercentage(): void
    {
        $interest = new Interest(
            InterestCode::FixedAmount,
            new \DateTimeImmutable(),
            20,
            20
        );
        $this->assertEquals(0.0, $interest->getTax());
    }

    #[Test]
    public function itCanGetTheInterestAmount(): void
    {
        $interest = new Interest(
            InterestCode::FixedAmount,
            new \DateTimeImmutable(),
            null,
            20
        );
        $this->assertEquals(20, $interest->getAmount());
    }

    #[Test]
    public function itGetZeroAmountForInterestsThatArentFixedAmount(): void
    {
        $interest = new Interest(
            InterestCode::PercentualAmount,
            new \DateTimeImmutable(),
            20,
            20
        );
        $this->assertEquals(0.0, $interest->getAmount());
    }
}
