<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Responses;

use Psr\Http\Message\ResponseInterface;
use Samfelgar\Inter\Common\PsrMessageUtils;
use Webmozart\Assert\Assert;

readonly class GetPixChargesResponse
{
    /**
     * @param GetPixChargeResponse[] $charges
     */
    public function __construct(
        public int $totalPages,
        public int $totalElements,
        public bool $last,
        public bool $first,
        public int $perPage,
        public int $elements,
        public array $charges,
    ) {
        Assert::allIsInstanceOf($this->charges, GetPixChargeResponse::class);
    }

    public static function fromResponse(ResponseInterface $response): GetPixChargesResponse
    {
        $data = PsrMessageUtils::bodyToArray($response);

        return new GetPixChargesResponse(
            $data['totalPaginas'],
            $data['totalElementos'],
            $data['ultimaPagina'],
            $data['primeiraPagina'],
            $data['tamanhoPagina'],
            $data['numeroDeElementos'],
            \array_map(GetPixChargeResponse::fromArray(...), $data['cobrancas']),
        );
    }
}
