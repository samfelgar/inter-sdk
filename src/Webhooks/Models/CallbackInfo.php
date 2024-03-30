<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Webhooks\Models;

readonly class CallbackInfo
{
    /**
     * @param array<string, mixed> $payload
     */
    public function __construct(
        public string $url,
        public array $payload,
        public int $attempt,
        public \DateTimeImmutable $dateTime,
        public bool $success,
        public int $httpStatus,
        public ?string $errorMessage,
    ) {
    }

    public static function fromArray(array $data): CallbackInfo
    {
        return new CallbackInfo(
            $data['webhookUrl'],
            $data['payload'],
            $data['numeroTentativa'],
            \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, $data['dataHoraDisparo']),
            (bool) $data['sucesso'],
            (int) $data['httpStatus'],
            $data['mensagemErro'] ?? null,
        );
    }
}
