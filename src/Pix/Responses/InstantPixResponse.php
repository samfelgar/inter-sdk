<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Pix\Responses;

use Psr\Http\Message\ResponseInterface;
use Samfelgar\Inter\Common\PsrMessageUtils;
use Samfelgar\Inter\Pix\Models\AdditionalInformation;
use Samfelgar\Inter\Pix\Models\ChargeType;
use Samfelgar\Inter\Pix\Models\Payer;
use Samfelgar\Inter\Pix\Models\PixStatus;
use Samfelgar\Inter\Pix\Models\Value;

readonly class InstantPixResponse
{
    public function __construct(
        public ?Payer $payer,
        public ?Localization $localization,
        public ?string $location,
        public PixStatus $status,
        public Value $value,
        public int $expirationInSeconds,
        public ?\DateTimeImmutable $createdAt,
        public string $txId,
        public int $revision,
        public ?string $copyAndPasteCode,
        public string $key,
        public ?string $payerRequest,
        public array $additionalInformation
    ) {
    }

    public static function fromArray(array $data): InstantPixResponse
    {
        $payer = null;
        if (isset($data['devedor'])) {
            $payer = new Payer(
                $data['devedor']['cpf'] ?? $data['devedor']['cnpj'],
                $data['devedor']['nome']
            );
        }

        $localization = null;
        if (isset($data['loc'])) {
            $localization = new Localization(
                $data['loc']['id'],
                $data['loc']['location'] ?? null,
                ChargeType::from($data['loc']['tipoCob']),
                \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, $data['loc']['criacao'])
            );
        }

        $createdAt = null;
        if (isset($data['calendario']['criacao'])) {
            $createdAt = \DateTimeImmutable::createFromFormat(\DateTimeInterface::RFC3339, $data['calendario']['criacao']);
        }

        $additionalInformation = \array_map(function (array $info): AdditionalInformation {
            return new AdditionalInformation($info['nome'], $info['valor']);
        }, $data['infoAdicionais'] ?? []);

        return new InstantPixResponse(
            $payer,
            $localization,
            $data['location'] ?? null,
            PixStatus::from($data['status']),
            Value::fromArray($data['valor']),
            $data['calendario']['expiracao'],
            $createdAt,
            $data['txid'],
            $data['revisao'],
            $data['pixCopiaECola'] ?? null,
            $data['chave'],
            $data['solicitacaoPagador'] ?? null,
            $additionalInformation
        );
    }

    public static function fromResponse(ResponseInterface $response): InstantPixResponse
    {
        $data = PsrMessageUtils::bodyToArray($response);
        return self::fromArray($data);
    }
}
