<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Pix\Models\Webhook;

use Psr\Http\Message\RequestInterface;
use Samfelgar\Inter\Common\PsrMessageUtils;
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
                \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $payload['horario']['solicitacao']),
                \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $payload['horario']['liquidacao']),
                ReturnStatus::from($payload['status']),
                $payload['motivo'] ?? null
            );
        }, $data['devolucoes'] ?? []);

        return new PixPayload(
            $data['endToEndId'],
            $data['txid'] ?? null,
            (float) $data['valor'],
            $data['chave'] ?? null,
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339_EXTENDED, $data['horario']),
            $data['infoPagador'] ?? null,
            $returns
        );
    }

    /**
     * @return PixPayload|PixPayload[]
     */
    public static function fromRequest(RequestInterface $request): PixPayload|array
    {
        $data = PsrMessageUtils::bodyToArray($request);

        if (\array_key_exists('pix', $data)) {
            $data = $data['pix'];
        }

        if (\array_is_list($data)) {
            return \array_map(self::fromArray(...), $data);
        }
        return self::fromArray($data);
    }
}
