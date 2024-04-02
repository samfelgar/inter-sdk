<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Requests;

use Samfelgar\Inter\PixCharges\Models\ChargeType;
use Samfelgar\Inter\PixCharges\Models\Situation;

readonly class GetPixChargesRequest
{
    public function __construct(
        public \DateTimeImmutable $startDate,
        public \DateTimeImmutable $endDate,
        public DateReference $dateReference = DateReference::DueDate,
        public Situation $situation = Situation::Pending,
        public ?ChargeType $chargeType = null,
        public ?string $name = null,
        public ?string $cpfCnpj = null,
        public ?string $yourNumber = null,
        public int $perPage = 100,
        public int $page = 1,
        public OrderBy $orderBy = OrderBy::Payer,
        public OrderType $orderType = OrderType::Asc,
    ) {
    }

    public function toArray(): array
    {
        $data = [
            'dataInicial' => $this->startDate->format('Y-m-d'),
            'dataFinal' => $this->endDate->format('Y-m-d'),
            'filtrarDataPor' => $this->dateReference->value,
            'situacao' => $this->situation->value,
            'paginacao.itensPorPagina' => $this->perPage,
            'ordenarPor' => $this->orderBy->value,
            'tipoOrdenacao' => $this->orderType->value,
        ];

        if ($this->chargeType !== null) {
            $data['tipoCobranca'] = $this->chargeType;
        }

        if ($this->name !== null) {
            $data['pessoaPagadora'] = $this->name;
        }

        if ($this->cpfCnpj !== null) {
            $data['cpfCnpjPessoaPagadora'] = $this->cpfCnpj;
        }

        if ($this->yourNumber !== null) {
            $data['seuNumero'] = $this->yourNumber;
        }

        if ($this->page > 1) {
            $data['paginacao.paginaAtual'] = $this->page;
        }

        return $data;
    }

    public function toSummaryArray(): array
    {
        $data = [
            'dataInicial' => $this->startDate->format('Y-m-d'),
            'dataFinal' => $this->endDate->format('Y-m-d'),
            'filtrarDataPor' => $this->dateReference->value,
            'situacao' => $this->situation->value,
        ];

        if ($this->name !== null) {
            $data['pessoaPagadora'] = $this->name;
        }

        if ($this->chargeType !== null) {
            $data['tipoCobranca'] = $this->chargeType;
        }

        if ($this->cpfCnpj !== null) {
            $data['cpfCnpjPessoaPagadora'] = $this->cpfCnpj;
        }

        if ($this->yourNumber !== null) {
            $data['seuNumero'] = $this->yourNumber;
        }

        return $data;
    }
}
