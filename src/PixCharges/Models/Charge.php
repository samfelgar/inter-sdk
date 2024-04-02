<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Models;

class Charge
{
    public function __construct(
        public readonly string $chargeIdentifier,
        public readonly string $yourNumber,
        public readonly \DateTimeImmutable $emission,
        public readonly \DateTimeImmutable $dueDate,
        public readonly float $amount,
        public readonly ChargeType $type,
        public readonly Situation $situation,
        public readonly \DateTimeImmutable $situationDate,
        public readonly float $receivedAmount,
        public readonly ChargeReceivementOrigin $origin,
        public readonly bool $archived,
        public readonly Discount $discount,
        public readonly Interest $interest,
        public readonly Moratorium $moratorium,
        public readonly Payer $payer,
    ) {
    }

    public static function fromArray(array $data): Charge
    {
        $phone = isset($data['pagador']['ddd'], $data['pagador']['telefone']) ? new Phone($data['pagador']['ddd'], $data['pagador']['telefone']) : null;
        $payer = new Payer(
            $data['pagador']['cpfCnpj'],
            PersonType::from($data['pagador']['tipoPessoa']),
            $data['pagador']['nome'],
            $data['pagador']['endereco'],
            $data['pagador']['numero'],
            $data['pagador']['complemento'],
            $data['pagador']['bairro'],
            $data['pagador']['cidade'],
            $data['pagador']['uf'],
            $data['pagador']['cep'],
            $data['pagador']['email'],
            $phone,
        );

        $interest = new Interest(
            InterestCode::from($data['multa']['codigo']),
            isset($data['multa']['taxa']) ? (float) $data['multa']['taxa'] : null,
            isset($data['multa']['valor']) ? (float) $data['multa']['valor'] : null,
        );

        $moratorium = new Moratorium(
            MoratoriumCode::from($data['mora']['codigo']),
            isset($data['mora']['taxa']) ? (float) $data['mora']['taxa'] : null,
            isset($data['mora']['valor']) ? (float) $data['mora']['valor'] : null,
        );

        $discount = Discount::noDiscount();

        if (isset($data['descontos'][0])) {
            $discount = new Discount(
                DiscountCode::from($data['descontos'][0]['codigo']),
                $data['descontos'][0]['quantidadeDias'],
                isset($data['descontos'][0]['taxa']) ? (float) $data['descontos'][0]['taxa'] : null,
                isset($data['descontos'][0]['valor']) ? (float) $data['descontos'][0]['valor'] : null,
            );
        }

        return new Charge(
            $data['codigoSolicitacao'],
            $data['seuNumero'],
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['dataEmissao']),
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['dataVencimento']),
            (float) $data['valorNominal'],
            ChargeType::from($data['tipoCobranca']),
            Situation::from($data['situacao']),
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['dataSituacao']),
            (float) $data['valorTotalRecebido'],
            ChargeReceivementOrigin::from($data['origemRecebimento']),
            (bool) $data['arquivada'],
            $discount,
            $interest,
            $moratorium,
            $payer,
        );
    }
}
