<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Banking\Requests;

use Samfelgar\Inter\Banking\Models\Pix;
use Webmozart\Assert\Assert;

readonly class CreatePixPaymentRequest implements \JsonSerializable
{
    public function __construct(
        public float $amount,
        public Pix $destination,
        public ?\DateTimeImmutable $date,
        public ?string $description,
    ) {
        Assert::nullOrLengthBetween($this->description, 1, 140);
    }

    public function jsonSerialize(): array
    {
        $data = [
            'valor' => $this->amount,
            'destinatario' => [
                'chave' => $this->destination->key,
                'tipo' => 'CHAVE', // for now, we only support this type of destination
            ],
        ];

        if ($this->description !== null) {
            $data['descricao'] = $this->description;
        }

        if ($this->date !== null) {
            $data['dataPagamento'] = $this->date->format('Y-m-d');
        }

        return $data;
    }
}
