<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Webhooks\Responses;

use Psr\Http\Message\ResponseInterface;
use Samfelgar\Inter\Common\ResponseUtils;
use Samfelgar\Inter\Webhooks\Models\CallbackInfo;
use Webmozart\Assert\Assert;

class QueryCallbacksResponse
{
    /**
     * @param CallbackInfo[] $data
     */
    public function __construct(
        public readonly int $totalElements,
        public readonly int $totalPages,
        public readonly bool $firstPage,
        public readonly bool $lastPage,
        public readonly array $data,
    ) {
        Assert::allIsInstanceOf($this->data, CallbackInfo::class);
    }

    public static function fromResponse(ResponseInterface $response): QueryCallbacksResponse
    {
        $data = ResponseUtils::responseToArray($response);
        return new QueryCallbacksResponse(
            $data['totalElementos'],
            $data['totalPaginas'],
            $data['primeiraPagina'],
            $data['ultimaPagina'],
            \array_map(CallbackInfo::fromArray(...), $data['data']),
        );
    }
}
