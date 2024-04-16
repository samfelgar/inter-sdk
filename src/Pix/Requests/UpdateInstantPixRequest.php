<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Pix\Requests;

use Samfelgar\Inter\Pix\Models\AdditionalInformation;
use Samfelgar\Inter\Pix\Models\ChargeType;
use Samfelgar\Inter\Pix\Models\Payer;
use Samfelgar\Inter\Pix\Models\PixStatus;
use Samfelgar\Inter\Pix\Models\Value;
use Webmozart\Assert\Assert;

readonly class UpdateInstantPixRequest implements \JsonSerializable
{
    /**
     * @param AdditionalInformation[] $additionalInformation
     */
    public function __construct(
        public string $txId,
        public ?Payer $payer,
        public Value $value,
        public string $key,
        public ?string $payerRequest = null,
        public array $additionalInformation = [],
        public int $expirationInSeconds = 86400,
        public ChargeType $chargeType = ChargeType::Cob,
    ) {
        Assert::allIsInstanceOf($this->additionalInformation, AdditionalInformation::class);
    }

    public function jsonSerialize(): array
    {
        $data = [
            'calendario' => [
                'expiracao' => $this->expirationInSeconds,
            ],
            'loc' => [
                'tipoCob' => $this->chargeType->value,
            ],
            'valor' => $this->value,
            'chave' => $this->key,
            'status' => PixStatus::RemovedByReceiver->value,
        ];

        if ($this->payerRequest !== null) {
            $data['solicitacaoPagador'] = $this->payerRequest;
        }

        if ($this->payer !== null) {
            $data['devedor'] = [
                'nome' => $this->payer->name,
            ];

            $documentKey = $this->payer->hasCpf() ? 'cpf' : 'cnpj';
            $data[$documentKey] = $this->payer->cpfCnpj;
        }

        if ($this->additionalInformation !== []) {
            $data['infoAdicionais'] = [];
            foreach ($this->additionalInformation as $additionalInformation) {
                $data['infoAdicionais'][] = [
                    'nome' => $additionalInformation->name,
                    'valor' => $additionalInformation->value,
                ];
            }
        }

        return $data;
    }
}
