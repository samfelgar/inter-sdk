<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Requests;

use Samfelgar\Inter\Charges\Models\Discount;
use Samfelgar\Inter\Charges\Models\Interest;
use Samfelgar\Inter\Charges\Models\InterestCode;
use Samfelgar\Inter\Charges\Models\Messages;
use Samfelgar\Inter\Charges\Models\Moratorium;
use Samfelgar\Inter\Charges\Models\MoratoriumCode;
use Samfelgar\Inter\Charges\Models\Payer;
use Samfelgar\Inter\Charges\Models\Recipient;
use Webmozart\Assert\Assert;

readonly class CreateChargeRequest implements \JsonSerializable
{
    private const MIN_AMOUNT = 2.5;

    /**
     * @var Discount[]
     */
    public array $discounts;

    /**
     * @param Discount[] $discounts
     */
    public function __construct(
        public string $yourNumber,
        public float $amount,
        public \DateTimeImmutable $dueDate,
        public int $daysUntilCancelling,
        public Payer $payer,
        public ?Messages $messages,
        array $discounts,
        public Interest $interest,
        public Moratorium $moratorium,
        public Recipient $recipient,
    ) {
        Assert::maxLength($this->yourNumber, 15);
        Assert::greaterThanEq($this->amount, self::MIN_AMOUNT);
        Assert::allIsInstanceOf($discounts, Discount::class);
        Assert::maxCount($discounts, 3);
        $this->discounts = \array_values($discounts);

        if ($this->interest->code !== InterestCode::NoInterest && $this->interest->getDate() < $this->dueDate) {
            throw new \InvalidArgumentException('Invalid interest date');
        }

        if ($this->moratorium->code !== MoratoriumCode::NoMoratorium && $this->moratorium->getDate() < $this->dueDate) {
            throw new \InvalidArgumentException('Invalid moratorium date');
        }
    }

    public function jsonSerialize(): array
    {
        return [
            "seuNumero" => $this->yourNumber,
            "valorNominal" => $this->amount,
            "dataVencimento" => $this->dueDate->format('Y-m-d'),
            "numDiasAgenda" => $this->daysUntilCancelling,
            "pagador" => [
                "cnpjCpf" => $this->payer->cpfCnpj,
                "nome" => $this->payer->name,
                "email" => $this->payer->email ?? '',
                "cep" => $this->payer->zipCode,
                "numero" => $this->payer->number ?? '',
                "complemento" => $this->payer->complement ?? '',
                "bairro" => $this->payer->neighborhood ?? '',
                "cidade" => $this->payer->city,
                "uf" => $this->payer->state,
                "endereco" => $this->payer->address,
                "ddd" => $this->payer->phone?->areaCode ?? '',
                "telefone" => $this->payer->phone?->number ?? '',
                "tipoPessoa" => $this->payer->type->value,
            ],
            "mensagem" => $this->serializeMessages(),
            ...$this->serializeDiscounts(),
            "multa" => $this->serializeInterest(),
            "mora" => $this->serializeMoratorium(),
            "beneficiarioFinal" => [
                "nome" => $this->recipient->name,
                "cpfCnpj" => $this->recipient->cpfCnpj,
                "tipoPessoa" => $this->recipient->type->value,
                "cep" => $this->recipient->zipCode,
                "endereco" => $this->recipient->address,
                "bairro" => $this->recipient->neighborhood,
                "cidade" => $this->recipient->city,
                "uf" => $this->recipient->state,
            ]
        ];
    }

    private function serializeMessages(): array
    {
        $messages = [];
        foreach ($this->messages?->getLines() ?? [] as $index => $message) {
            $lineIndex = \sprintf('linha%d', $index + 1);
            $messages[$lineIndex] = $message;
        }
        return $messages;
    }

    private function serializeDiscounts(): array
    {
        $discounts = [];
        foreach ($this->discounts as $index => $discount) {
            $discountIndex = \sprintf('desconto%d', $index + 1);
            $discountValue = [
                "codigoDesconto" => $discount->code->value,
                "taxa" => $discount->getTax(),
                "valor" => $discount->getAmount(),
            ];

            $date = $discount->getDate();
            if ($date !== null) {
                $discountValue['data'] = $date->format('Y-m-d');
            }

            $discounts[$discountIndex] = $discountValue;
        }
        return $discounts;
    }

    private function serializeInterest(): array
    {
        $interest = [
            "codigoMulta" => $this->interest->code->value,
            "taxa" => $this->interest->getTax(),
            "valor" => $this->interest->getAmount(),
        ];

        $date = $this->interest->getDate();
        if ($date !== null) {
            $interest['data'] = $date->format('Y-m-d');
        }

        return $interest;
    }

    private function serializeMoratorium(): array
    {
        $moratorium = [
            "codigoMora" => $this->moratorium->code->value,
            "taxa" => $this->moratorium->getTax(),
            "valor" => $this->moratorium->getAmount(),
        ];

        $date = $this->moratorium->getDate();
        if ($date !== null) {
            $moratorium['data'] = $date->format('Y-m-d');
        }

        return $moratorium;
    }
}
