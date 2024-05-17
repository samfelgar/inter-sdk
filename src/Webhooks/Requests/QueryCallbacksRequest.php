<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Webhooks\Requests;

readonly class QueryCallbacksRequest
{
    public function __construct(
        public \DateTimeImmutable $startDate,
        public \DateTimeImmutable $endDate,
        public int $page = 0,
        public int $length = 20,
        public ?string $ourNumber = null,
        public ?string $txId = null,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'dataHoraInicio' => $this->startDate->format(\DateTimeInterface::RFC3339_EXTENDED),
            'dataHoraFim' => $this->startDate->format(\DateTimeInterface::RFC3339_EXTENDED),
            'pagina' => $this->page,
            'tamanhoPagina' => $this->length,
        ];

        if ($this->ourNumber !== null) {
            $data['nossoNumero'] = $this->ourNumber;
        }

        if ($this->txId !== null) {
            $data['txid'] = $this->txId;
        }

        return $data;
    }
}
