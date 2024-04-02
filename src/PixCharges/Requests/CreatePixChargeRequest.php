<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Requests;

use Samfelgar\Inter\PixCharges\Models\Discount;
use Samfelgar\Inter\PixCharges\Models\DiscountCode;
use Samfelgar\Inter\PixCharges\Models\Interest;
use Samfelgar\Inter\PixCharges\Models\InterestCode;
use Samfelgar\Inter\PixCharges\Models\Messages;
use Samfelgar\Inter\PixCharges\Models\Moratorium;
use Samfelgar\Inter\PixCharges\Models\MoratoriumCode;
use Samfelgar\Inter\PixCharges\Models\Payer;
use Samfelgar\Inter\PixCharges\Models\Recipient;
use Webmozart\Assert\Assert;

class CreatePixChargeRequest implements \JsonSerializable
{
    private const MIN_AMOUNT = 2.5;

    public function __construct(
        public string $yourNumber,
        public float $amount,
        public \DateTimeImmutable $dueDate,
        public int $daysUntilCancelling,
        public Payer $payer,
        public ?Messages $messages,
        public Discount $discount,
        public Interest $interest,
        public Moratorium $moratorium,
        public Recipient $recipient,
    ) {
        Assert::maxLength($this->yourNumber, 15);
        Assert::greaterThanEq($this->amount, self::MIN_AMOUNT);
    }

    public function jsonSerialize(): array
    {
        $data = [
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

        if ($this->discount->code !== DiscountCode::NoDiscount) {
            $data['desconto'] = $this->serializeDiscount();
        }

        if ($this->interest->code !== InterestCode::NoInterest) {
            $data['multa'] = $this->serializeInterest();
        }

        if ($this->moratorium->code !== MoratoriumCode::NoMoratorium) {
            $data['mora'] = $this->serializeMoratorium();
        }

        return $data;
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

    private function serializeDiscount(): array
    {
        $discount = [
            "codigo" => $this->discount->code->value,
            'quantidadeDias' => $this->discount->daysBeforeDueDate,
        ];

        if ($this->discount->code === DiscountCode::PercentageValue) {
            $discount['taxa'] = $this->discount->getTax();
        }

        if ($this->discount->code === DiscountCode::FixedValue) {
            $discount['valor'] = $this->discount->getAmount();
        }

        return $discount;
    }

    private function serializeInterest(): array
    {
        $interest = [
            "codigo" => $this->interest->code->value,
        ];

        if ($this->interest->code === InterestCode::PercentualAmount) {
            $interest['taxa'] = $this->interest->getTax();
        }

        if ($this->interest->code === InterestCode::FixedAmount) {
            $interest['valor'] = $this->interest->getAmount();
        }

        return $interest;
    }

    private function serializeMoratorium(): array
    {
        $moratorium = [
            "codigo" => $this->moratorium->code->value,
        ];

        if ($this->moratorium->code === MoratoriumCode::MonthlyTax) {
            $moratorium['taxa'] = $this->moratorium->getTax();
        }

        if ($this->moratorium->code === MoratoriumCode::DailyAmount) {
            $moratorium['valor'] = $this->moratorium->getAmount();
        }

        return $moratorium;
    }
}
