<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Pix\Models\Webhook;

use Psr\Http\Message\ResponseInterface;
use Samfelgar\Inter\Common\ResponseUtils;
use Webmozart\Assert\Assert;

readonly class PixPayload
{
    /**
     * @param ReturnPayload[] $returns
     * @param array<string, mixed> $amountComponents
     */
    public function __construct(
        public string $endToEndId,
        public ?string $txId,
        public float $amount,
        public ?string $pixKey,
        public \DateTimeImmutable $processedAt,
        public ?string $payerInformation,
        public array $returns,
        public array $amountComponents = [],
    ) {
        Assert::allIsInstanceOf($this->returns, ReturnPayload::class);
    }

    public static function fromArray(array $data): PixPayload
    {
        $returns = \array_map(function (array $payload): ReturnPayload {
            return new ReturnPayload(
                $payload['id'],
                $payload['rtrId'],
                (float) $payload['valor'],
                \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, $payload['horario']['solicitacao']),
                \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, $payload['horario']['liquidacao']),
                ReturnStatus::from($payload['status']),
                $payload['motivo'] ?? null
            );
        }, $data['devolucoes'] ?? []);

        return new PixPayload(
            $data['endToEndId'],
            $data['txid'] ?? null,
            (float) $data['valor'],
            $data['chave'] ?? null,
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, $data['horario']),
            $data['infoPagador'] ?? null,
            $returns
        );
    }

    /**
     * @return PixPayload|PixPayload[]
     */
    public static function fromResponse(ResponseInterface $response): PixPayload|array
    {
        $data = ResponseUtils::responseToArray($response);
        if (\array_is_list($data)) {
            return \array_map(self::fromArray(...), $data);
        }
        return self::fromArray($data);
    }
}
