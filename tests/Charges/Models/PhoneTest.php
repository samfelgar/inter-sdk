<?php

namespace Samfelgar\Inter\Tests\Charges\Models;

use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Inter\Charges\Models\Phone;

class PhoneTest extends TestCase
{
    #[Test]
    public function itValidatesTheAreaCodeLengthToBeLessThanTwo(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Phone('999', '999999999');
    }

    #[Test]
    public function itValidateTheNumberLengthToBeLessThanNine(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        new Phone('99', '9999999999');
    }

    #[Test]
    public function itCanInstantiateAPhone(): void
    {
        $phone = new Phone('99', '999999999');
        $this->assertInstanceOf(Phone::class, $phone);
    }

    #[Test]
    public function itSanitizesTheInputs(): void
    {
        $phone = new Phone('9 9', '9 9999-9999');
        $this->assertEquals('999999999', $phone->number);
        $this->assertEquals('99', $phone->areaCode);
    }
}
