<?php

namespace Samfelgar\Inter\Tests\Charges\Requests;

use Faker\Factory;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Inter\Charges\Models\Discount;
use Samfelgar\Inter\Charges\Models\DiscountCode;
use Samfelgar\Inter\Charges\Models\Interest;
use Samfelgar\Inter\Charges\Models\InterestCode;
use Samfelgar\Inter\Charges\Models\Messages;
use Samfelgar\Inter\Charges\Models\Moratorium;
use Samfelgar\Inter\Charges\Models\MoratoriumCode;
use Samfelgar\Inter\Charges\Models\Payer;
use Samfelgar\Inter\Charges\Models\PersonType;
use Samfelgar\Inter\Charges\Models\Recipient;
use Samfelgar\Inter\Charges\Requests\CreateChargeRequest;

class CreateChargeRequestTest extends TestCase
{
    #[Test]
    public function itCanCreateAChargeRequest(): void
    {
        $faker = Factory::create('pt_BR');
        $request = new CreateChargeRequest(
            $faker->numerify(),
            200,
            (new \DateTimeImmutable())->add(new \DateInterval('P1M')),
            30,
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
                $faker->email(),
                null
            ),
            null,
            [],
            new Interest(InterestCode::NoInterest, null, null, null),
            new Moratorium(MoratoriumCode::NoMoratorium, null, null, null),
            new Recipient(
                $faker->name(),
                $faker->cpf(),
                PersonType::Natural,
                $faker->numerify('########'),
                $faker->address(),
                $faker->secondaryAddress(),
                $faker->city(),
                $faker->stateAbbr(),
            )
        );

        $this->assertInstanceOf(CreateChargeRequest::class, $request);
    }

    #[Test]
    public function isValidatesTheMinimumAmount(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $faker = Factory::create('pt_BR');
        new CreateChargeRequest(
            $faker->numerify(),
            1,
            (new \DateTimeImmutable())->add(new \DateInterval('P1M')),
            30,
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
                $faker->email(),
                null
            ),
            null,
            [],
            new Interest(InterestCode::NoInterest, null, null, null),
            new Moratorium(MoratoriumCode::NoMoratorium, null, null, null),
            new Recipient(
                $faker->name(),
                $faker->cpf(),
                PersonType::Natural,
                $faker->numerify('########'),
                $faker->address(),
                $faker->secondaryAddress(),
                $faker->city(),
                $faker->stateAbbr(),
            )
        );
    }

    #[Test]
    public function isValidatesYourNumberLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $faker = Factory::create('pt_BR');
        new CreateChargeRequest(
            $faker->numerify('####################'),
            200,
            (new \DateTimeImmutable())->add(new \DateInterval('P1M')),
            30,
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
                $faker->email(),
                null
            ),
            null,
            [],
            new Interest(InterestCode::NoInterest, null, null, null),
            new Moratorium(MoratoriumCode::NoMoratorium, null, null, null),
            new Recipient(
                $faker->name(),
                $faker->cpf(),
                PersonType::Natural,
                $faker->numerify('########'),
                $faker->address(),
                $faker->secondaryAddress(),
                $faker->city(),
                $faker->stateAbbr(),
            )
        );
    }

    #[Test]
    public function isValidatesTheArrayOfDiscounts(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Expected an instance of/');

        $faker = Factory::create('pt_BR');
        new CreateChargeRequest(
            $faker->numerify(),
            200,
            (new \DateTimeImmutable())->add(new \DateInterval('P1M')),
            30,
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
                $faker->email(),
                null
            ),
            null,
            ['a', 'b', 'c'],
            new Interest(InterestCode::NoInterest, null, null, null),
            new Moratorium(MoratoriumCode::NoMoratorium, null, null, null),
            new Recipient(
                $faker->name(),
                $faker->cpf(),
                PersonType::Natural,
                $faker->numerify('########'),
                $faker->address(),
                $faker->secondaryAddress(),
                $faker->city(),
                $faker->stateAbbr(),
            )
        );
    }

    #[Test]
    public function isValidatesTheDiscountsArrayLength(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessageMatches('/Expected an array to contain at most/');

        $faker = Factory::create('pt_BR');
        $discount = new Discount(DiscountCode::NoDiscount, null, null, null);

        new CreateChargeRequest(
            $faker->numerify(),
            100,
            (new \DateTimeImmutable())->add(new \DateInterval('P1M')),
            30,
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
                $faker->email(),
                null
            ),
            null,
            [$discount, $discount, $discount, $discount],
            new Interest(InterestCode::NoInterest, null, null, null),
            new Moratorium(MoratoriumCode::NoMoratorium, null, null, null),
            new Recipient(
                $faker->name(),
                $faker->cpf(),
                PersonType::Natural,
                $faker->numerify('########'),
                $faker->address(),
                $faker->secondaryAddress(),
                $faker->city(),
                $faker->stateAbbr(),
            )
        );
    }

    #[Test]
    public function isValidatesTheInterestDate(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid interest date');

        $faker = Factory::create('pt_BR');
        $interest = new Interest(InterestCode::PercentualAmount, new \DateTimeImmutable(), 2.0, null);

        new CreateChargeRequest(
            $faker->numerify(),
            100,
            (new \DateTimeImmutable())->add(new \DateInterval('P1M')),
            30,
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
                $faker->email(),
                null
            ),
            null,
            [],
            $interest,
            new Moratorium(MoratoriumCode::NoMoratorium, null, null, null),
            new Recipient(
                $faker->name(),
                $faker->cpf(),
                PersonType::Natural,
                $faker->numerify('########'),
                $faker->address(),
                $faker->secondaryAddress(),
                $faker->city(),
                $faker->stateAbbr(),
            )
        );
    }

    #[Test]
    public function isValidatesTheMoratoriumDate(): void
    {
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage('Invalid moratorium date');

        $faker = Factory::create('pt_BR');
        $interest = new Interest(InterestCode::PercentualAmount, (new \DateTimeImmutable())->add(new \DateInterval('P2M')), 2.0, null);
        $moratorium = new Moratorium(MoratoriumCode::DailyAmount, new \DateTimeImmutable(), null, 20.5);

        new CreateChargeRequest(
            $faker->numerify(),
            100,
            (new \DateTimeImmutable())->add(new \DateInterval('P1M')),
            30,
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
                $faker->email(),
                null
            ),
            null,
            [],
            $interest,
            $moratorium,
            new Recipient(
                $faker->name(),
                $faker->cpf(),
                PersonType::Natural,
                $faker->numerify('########'),
                $faker->address(),
                $faker->secondaryAddress(),
                $faker->city(),
                $faker->stateAbbr(),
            )
        );
    }

    #[Test]
    public function itGeneratesAValidJson(): void
    {
        $faker = Factory::create('pt_BR');

        $messages = new Messages();
        $messages->addLine(1, 'Test1');
        $messages->addLine(2, 'Test2');
        $discount = new Discount(DiscountCode::NoDiscount, null, null, null);
        $interest = new Interest(InterestCode::NoInterest, null, null, null);
        $moratorium = new Moratorium(MoratoriumCode::NoMoratorium, null, null, null);

        $request = new CreateChargeRequest(
            $faker->numerify(),
            200,
            (new \DateTimeImmutable())->add(new \DateInterval('P1M')),
            30,
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
                $faker->email(),
                null
            ),
            $messages,
            [$discount],
            $interest,
            $moratorium,
            new Recipient(
                $faker->name(),
                $faker->cpf(),
                PersonType::Natural,
                $faker->numerify('########'),
                $faker->address(),
                $faker->secondaryAddress(),
                $faker->city(),
                $faker->stateAbbr(),
            )
        );

        $data = [
            "seuNumero" => $request->yourNumber,
            "valorNominal" => $request->amount,
            "dataVencimento" => $request->dueDate->format('Y-m-d'),
            "numDiasAgenda" => $request->daysUntilCancelling,
            "pagador" => [
                "cnpjCpf" => $request->payer->cpfCnpj,
                "nome" => $request->payer->name,
                "email" => $request->payer->email ?? '',
                "cep" => $request->payer->zipCode,
                "numero" => $request->payer->number ?? '',
                "complemento" => $request->payer->complement ?? '',
                "bairro" => $request->payer->neighborhood ?? '',
                "cidade" => $request->payer->city,
                "uf" => $request->payer->state,
                "endereco" => $request->payer->address,
                "ddd" => $request->payer->phone?->areaCode ?? '',
                "telefone" => $request->payer->phone?->number ?? '',
                "tipoPessoa" => $request->payer->type->value,
            ],
            "mensagem" => [
                'linha1' => $messages->getLine(1),
                'linha2' => $messages->getLine(2),
            ],
            'desconto1' => [
                'codigoDesconto' => $discount->code->value,
                'taxa' => $discount->getTax(),
                'valor' => $discount->getAmount(),
            ],
            "multa" => [
                "codigoMulta" => $interest->code->value,
                "taxa" => $interest->getTax(),
                "valor" => $interest->getAmount(),
            ],
            "mora" => [
                "codigoMora" => $moratorium->code->value,
                "taxa" => $moratorium->getTax(),
                "valor" => $moratorium->getAmount(),
            ],
            "beneficiarioFinal" => [
                "nome" => $request->recipient->name,
                "cpfCnpj" => $request->recipient->cpfCnpj,
                "tipoPessoa" => $request->recipient->type->value,
                "cep" => $request->recipient->zipCode,
                "endereco" => $request->recipient->address,
                "bairro" => $request->recipient->neighborhood,
                "cidade" => $request->recipient->city,
                "uf" => $request->recipient->state,
            ]
        ];

        $this->assertJsonStringEqualsJsonString(\json_encode($data), \json_encode($request));
    }
}
