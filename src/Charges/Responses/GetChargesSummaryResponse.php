<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Responses;

use Psr\Http\Message\ResponseInterface;
use Samfelgar\Inter\Common\ResponseUtils;

readonly class GetChargesSummaryResponse
{
    public function __construct(
        public SummaryItem $paid,
        public SummaryItem $pending,
        public SummaryItem $pastDue,
        public SummaryItem $cancelled,
        public SummaryItem $expired,
    ) {
    }

    public static function fromResponse(ResponseInterface $response): GetChargesSummaryResponse
    {
        $data = ResponseUtils::responseToArray($response);
        return new GetChargesSummaryResponse(
            new SummaryItem($data['pagos']['quantidade'], $data['pagos']['valor']),
            new SummaryItem($data['abertos']['quantidade'], $data['abertos']['valor']),
            new SummaryItem($data['vencidos']['quantidade'], $data['vencidos']['valor']),
            new SummaryItem($data['cancelados']['quantidade'], $data['cancelados']['valor']),
            new SummaryItem($data['expirados']['quantidade'], $data['expirados']['valor']),
        );
    }
}
