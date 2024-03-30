<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Models;

use Psr\Http\Message\ResponseInterface;

class Charge
{
    public function __construct(
        public readonly string $recipientName,
        public readonly string $recipientCpfCnpj,
        public readonly PersonType $recipientType,
        public readonly string $checkingAccount,
        public readonly string $ourNumber,
        public readonly string $yourNumber,
        public readonly Payer $payer,
        public readonly ?string $cancelReason,
        public readonly Situation $situation,
        public readonly \DateTimeImmutable $situationDate,
        public readonly \DateTimeImmutable $dueDate,
        public readonly float $amount,
        public readonly float $receivedAmount,
        public readonly \DateTimeImmutable $emission,
        public readonly \DateTimeImmutable $limitDate,
        public readonly string $speciesCode,
        public readonly string $barCode,
        public readonly string $line,
        public readonly string $origin,
        public readonly ?Messages $messages,
        public readonly array $discounts,
        public readonly Interest $interest,
        public readonly Moratorium $moratorium,
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
            $data['pagador']['city'],
            $data['pagador']['uf'],
            $data['pagador']['cep'],
            $data['pagador']['email'],
            $phone,
        );

        $messages = new Messages();
        foreach ($data['mensagem'] ?? [] as $index => $message) {
            $messages->addLine((int) \str_replace('linha', '', $index), $message);
        }

        $interest = new Interest(
            InterestCode::from($data['multa']['codigoMulta']),
            isset($data['multa']['data']) ? \DateTimeImmutable::createFromFormat('Y-m-d', $data['multa']['data']) : null,
            isset($data['multa']['taxa']) ? (float) $data['multa']['taxa'] : null,
            isset($data['multa']['valor']) ? (float) $data['multa']['valor'] : null,
        );

        $moratorium = new Moratorium(
            MoratoriumCode::from($data['mora']['codigoMora']),
            isset($data['mora']['data']) ? \DateTimeImmutable::createFromFormat('Y-m-d', $data['mora']['data']) : null,
            isset($data['mora']['taxa']) ? (float) $data['mora']['taxa'] : null,
            isset($data['mora']['valor']) ? (float) $data['mora']['valor'] : null,
        );

        $discounts = [
            new Discount(
                DiscountCode::from($data['desconto1']['codigoDesconto']),
                isset($data['desconto1']['data']) ? \DateTimeImmutable::createFromFormat('Y-m-d', $data['desconto1']['data']) : null,
                isset($data['desconto1']['taxa']) ? (float) $data['desconto1']['taxa'] : null,
                isset($data['desconto1']['valor']) ? (float) $data['desconto1']['valor'] : null,
            ),
            new Discount(
                DiscountCode::from($data['desconto2']['codigoDesconto']),
                isset($data['desconto2']['data']) ? \DateTimeImmutable::createFromFormat('Y-m-d', $data['desconto2']['data']) : null,
                isset($data['desconto2']['taxa']) ? (float) $data['desconto2']['taxa'] : null,
                isset($data['desconto2']['valor']) ? (float) $data['desconto2']['valor'] : null,
            ),
            new Discount(
                DiscountCode::from($data['desconto3']['codigoDesconto']),
                isset($data['desconto3']['data']) ? \DateTimeImmutable::createFromFormat('Y-m-d', $data['desconto3']['data']) : null,
                isset($data['desconto3']['taxa']) ? (float) $data['desconto3']['taxa'] : null,
                isset($data['desconto3']['valor']) ? (float) $data['desconto3']['valor'] : null,
            ),
        ];

        return new Charge(
            $data['nomeBeneficiario'],
            $data['cnpjCpfBeneficiario'],
            PersonType::from($data['tipoPessoaBeneficiario']),
            $data['contaCorrente'] ?? null,
            $data['nossoNumero'],
            $data['seuNumero'],
            $payer,
            $data['motivoCancelamento'] ?? null,
            Situation::from($data['situacao']),
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['dataHoraSituacao']),
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['dataVencimento']),
            (float) $data['valorNominal'],
            (float) $data['valorTotalRecebimento'],
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['dataEmissao']),
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['dataLimite']),
            $data['codigoEspecie'],
            $data['codigoBarras'],
            $data['linhaDigitavel'],
            $data['origem'],
            $messages,
            $discounts,
            $interest,
            $moratorium,
        );
    }
}
