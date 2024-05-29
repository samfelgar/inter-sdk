<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Banking\Responses;

use Psr\Http\Message\ResponseInterface;
use Samfelgar\Inter\Banking\Models\PaymentRequestStatus;
use Samfelgar\Inter\Common\PsrMessageUtils;

readonly class CreatePixPaymentResponse
{
    public function __construct(
        public PaymentRequestStatus $status,
        public string $requestCode,
        public \DateTimeImmutable $paymentDate,
        public \DateTimeImmutable $operationDate,
    ) {
    }

    public static function fromArray(array $data): CreatePixPaymentResponse
    {
        return new CreatePixPaymentResponse(
            PaymentRequestStatus::from($data['tipoRetorno']),
            $data['codigoSolicitacao'],
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['dataPagamento']),
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['dataOperacao']),
        );
    }

    public static function fromResponse(ResponseInterface $response): CreatePixPaymentResponse
    {
        return self::fromArray(PsrMessageUtils::bodyToArray($response));
    }
}
