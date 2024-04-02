<?php

namespace Samfelgar\Inter\Tests\Charges\Models;

use Faker\Factory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Inter\Charges\Models\Payer;
use Samfelgar\Inter\Charges\Models\PersonType;
use Samfelgar\Inter\Charges\Models\Phone;

class PayerTest extends TestCase
{
    #[Test]
    public function itCanInstantiateAPayer(): void
    {
        $faker = Factory::create('pt_BR');
        $payer = new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            null,
            null,
            null,
            $faker->city(),
            $faker->stateAbbr(),
            $faker->numerify('########'),
            $faker->email(),
            new Phone('99', '999999999')
        );
        $this->assertInstanceOf(Payer::class, $payer);
    }

    #[Test]
    public function itValidatesTheLengthOfCpfCnpj(): void
    {
        $faker = Factory::create('pt_BR');
        $this->expectException(\InvalidArgumentException::class);
        new Payer(
            $faker->numerify('999999999999'),
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            null,
            null,
            null,
            $faker->city(),
            $faker->stateAbbr(),
            $faker->numerify('########'),
            $faker->email(),
            null
        );
    }

    #[Test]
    public function itSanitizesTheCpfCnpj(): void
    {
        $faker = Factory::create('pt_BR');
        $payer = new Payer(
            '999.999.999-99',
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            null,
            null,
            null,
            $faker->city(),
            $faker->stateAbbr(),
            $faker->numerify('########'),
            $faker->email(),
            new Phone('99', '999999999')
        );
        $this->assertEquals('99999999999', $payer->cpfCnpj);
    }

    #[Test]
    public function itSanitizesTheZipCode(): void
    {
        $faker = Factory::create('pt_BR');
        $payer = new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            null,
            null,
            null,
            $faker->city(),
            $faker->stateAbbr(),
            '99.999-999',
            $faker->email(),
            new Phone('99', '999999999')
        );
        $this->assertEquals('99999999', $payer->zipCode);
    }

    #[Test]
    public function itValidatesTheLengthOfName(): void
    {
        $faker = Factory::create('pt_BR');
        $this->expectException(\InvalidArgumentException::class);
        new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->realTextBetween(),
            $faker->address(),
            null,
            null,
            null,
            $faker->city(),
            $faker->stateAbbr(),
            $faker->numerify('########'),
            $faker->email(),
            null
        );
    }

    #[Test]
    public function itValidatesTheLengthOfAddress(): void
    {
        $faker = Factory::create('pt_BR');
        $this->expectException(\InvalidArgumentException::class);
        new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->name(),
            $faker->realTextBetween(),
            null,
            null,
            null,
            $faker->city(),
            $faker->stateAbbr(),
            $faker->numerify('########'),
            $faker->email(),
            null
        );
    }

    #[Test]
    public function itValidatesTheLengthOfNumber(): void
    {
        $faker = Factory::create('pt_BR');
        $this->expectException(\InvalidArgumentException::class);
        new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            $faker->realTextBetween(),
            null,
            null,
            $faker->city(),
            $faker->stateAbbr(),
            $faker->numerify('########'),
            $faker->email(),
            null
        );
    }

    #[Test]
    public function itValidatesTheLengthOfComplement(): void
    {
        $faker = Factory::create('pt_BR');
        $this->expectException(\InvalidArgumentException::class);
        new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            null,
            $faker->realTextBetween(),
            null,
            $faker->city(),
            $faker->stateAbbr(),
            $faker->numerify('########'),
            $faker->email(),
            null
        );
    }

    #[Test]
    public function itValidatesTheLengthOfNeighborhood(): void
    {
        $faker = Factory::create('pt_BR');
        $this->expectException(\InvalidArgumentException::class);
        new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            null,
            null,
            $faker->realTextBetween(),
            $faker->city(),
            $faker->stateAbbr(),
            $faker->numerify('########'),
            $faker->email(),
            null
        );
    }

    #[Test]
    public function itValidatesTheLengthOfCity(): void
    {
        $faker = Factory::create('pt_BR');
        $this->expectException(\InvalidArgumentException::class);
        new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            null,
            null,
            null,
            $faker->realTextBetween(),
            $faker->stateAbbr(),
            $faker->numerify('########'),
            $faker->email(),
            null
        );
    }

    #[Test]
    public function itValidatesTheLengthOfState(): void
    {
        $faker = Factory::create('pt_BR');
        $this->expectException(\InvalidArgumentException::class);
        new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            null,
            null,
            null,
            $faker->city(),
            $faker->lexify(),
            $faker->numerify('########'),
            $faker->email(),
            null
        );
    }

    #[Test]
    public function itValidatesTheLengthOfZipCode(): void
    {
        $faker = Factory::create('pt_BR');
        $this->expectException(\InvalidArgumentException::class);
        new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            null,
            null,
            null,
            $faker->city(),
            $faker->stateAbbr(),
            $faker->numerify('#########'),
            $faker->email(),
            null
        );
    }

    #[Test]
    public function itValidatesTheLengthOfEmail(): void
    {
        $faker = Factory::create('pt_BR');
        $this->expectException(\InvalidArgumentException::class);
        new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            null,
            null,
            null,
            $faker->city(),
            $faker->stateAbbr(),
            $faker->numerify('########'),
            $faker->realTextBetween(),
            null
        );
    }

    #[Test]
    public function itValidatesTheEmail(): void
    {
        $faker = Factory::create('pt_BR');
        $this->expectException(\InvalidArgumentException::class);
        new Payer(
            $faker->cpf(),
            PersonType::Natural,
            $faker->name(),
            $faker->address(),
            null,
            null,
            null,
            $faker->city(),
            $faker->stateAbbr(),
            $faker->numerify('########'),
            'example', //wrong data
            null
        );
    }
}
