<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Banking\Models\Webhook;

use Psr\Http\Message\RequestInterface;
use Samfelgar\Inter\Banking\Models\PaymentStatus;
use Samfelgar\Inter\Common\PsrMessageUtils;

readonly class PaymentPixWebhookPayload
{
    public function __construct(
        public string $pixKey,
        public string $requestCode,
        public \DateTimeImmutable $datetime,
        public \DateTimeImmutable $requestedAt,
        public string $endToEnd,
        public PaymentStatus $status,
        public string $updateType,
        public float $amount,
        public Payee $payee,
    ) {
    }

    public static function fromArray(array $data): PaymentPixWebhookPayload
    {
        return new PaymentPixWebhookPayload(
            $data['chave'],
            $data['codigoSolicitacao'],
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $data['dataHoraMovimento']),
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $data['dataHoraSolicitacao']),
            $data['endToEnd'],
            PaymentStatus::from($data['status']),
            $data['tipoMovimentacao'],
            (float) $data['valor'],
            new Payee($data['recebedor']['cpfCnpj'], $data['recebedor']['nome']),
        );
    }

    /**
     * @return PaymentPixWebhookPayload|PaymentPixWebhookPayload[]
     */
    public static function fromRequest(RequestInterface $request): PaymentPixWebhookPayload|array
    {
        $data = PsrMessageUtils::bodyToArray($request);
        if (\array_is_list($data)) {
            return \array_map(self::fromArray(...), $data);
        }
        return self::fromArray($data);
    }
}
