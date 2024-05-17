<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Models;

use Psr\Http\Message\RequestInterface;
use Samfelgar\Inter\Common\PsrMessageUtils;

readonly class PixChargeWebhookPayload
{
    public function __construct(
        public string $chargeId,
        public string $yourNumber,
        public Situation $situation,
        public \DateTimeImmutable $situationDate,
        public float $receivedAmount,
        public ChargeReceivementOrigin $origin,
        public string $ourNumber,
        public string $barCode,
        public string $line,
        public string $pixId,
        public string $copyAndPasteCode,
    ) {
    }

    public static function fromArray(array $data): PixChargeWebhookPayload
    {
        return new PixChargeWebhookPayload(
            $data['codigoSolicitacao'],
            $data['seuNumero'],
            Situation::from($data['situacao']),
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $data['dataHoraSituacao']),
            (float) $data['valorTotalRecebido'],
            ChargeReceivementOrigin::from($data['origemRecebimento']),
            $data['nossoNumero'],
            $data['codigoBarras'],
            $data['linhaDigitavel'],
            $data['txid'],
            $data['pixCopiaECola'],
        );
    }

    /**
     * @return PixChargeWebhookPayload|PixChargeWebhookPayload[]
     */
    public static function fromRequest(RequestInterface $request): PixChargeWebhookPayload|array
    {
        $data = PsrMessageUtils::bodyToArray($request);
        if (\array_is_list($data)) {
            return \array_map(self::fromArray(...), $data);
        }
        return self::fromArray($data);
    }
}
