<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Models;

use Psr\Http\Message\RequestInterface;
use Samfelgar\Inter\Common\PsrMessageUtils;

readonly class ChargeWebhookPayload
{
    public function __construct(
        public string $ourNumber,
        public string $yourNumber,
        public Situation $situation,
        public \DateTimeImmutable $situationDate,
        public float $amount,
        public float $receivedAmount,
        public string $barCode,
        public string $line,
    ) {
    }

    /**
     * @param array<string, string|float|int> $data
     */
    public static function fromArray(array $data): ChargeWebhookPayload
    {
        return new ChargeWebhookPayload(
            $data['nossoNumero'],
            $data['seuNumero'],
            Situation::from($data['situacao']),
            \DateTimeImmutable::createFromFormat('Y-m-d', $data['dataHoraSituacao']),
            $data['valorNominal'],
            $data['valorTotalRecebimento'],
            $data['codigoBarras'],
            $data['linhaDigitavel'],
        );
    }

    /**
     * @return ChargeWebhookPayload|ChargeWebhookPayload[]
     */
    public static function fromRequest(RequestInterface $request): ChargeWebhookPayload|array
    {
        $data = PsrMessageUtils::bodyToArray($request);
        if (\array_is_list($data)) {
            return \array_map(self::fromArray(...), $data);
        }
        return self::fromArray($data);
    }
}
