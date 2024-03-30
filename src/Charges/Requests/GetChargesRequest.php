<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Requests;

use Samfelgar\Inter\Charges\Models\Situation;

readonly class GetChargesRequest
{
    public function __construct(
        public \DateTimeImmutable $startDate,
        public \DateTimeImmutable $endDate,
        public DateReference $dateReference = DateReference::DueDate,
        public Situation $situation = Situation::Pending,
        public ?string $name = null,
        public ?string $email = null,
        public ?string $cpfCnpj = null,
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
            'itensPorPagina' => $this->perPage,
            'ordenaPor' => $this->orderBy->value,
            'tipoOrdenacao' => $this->orderType->value,
        ];

        if ($this->name !== null) {
            $data['nome'] = $this->name;
        }

        if ($this->email !== null) {
            $data['email'] = $this->email;
        }

        if ($this->cpfCnpj !== null) {
            $data['cpfCnpj'] = $this->cpfCnpj;
        }

        if ($this->page > 1) {
            $data['paginaAtual'] = $this->page;
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
            $data['nome'] = $this->name;
        }

        if ($this->email !== null) {
            $data['email'] = $this->email;
        }

        if ($this->cpfCnpj !== null) {
            $data['cpfCnpj'] = $this->cpfCnpj;
        }

        return $data;
    }
}
