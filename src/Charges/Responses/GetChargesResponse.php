<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Responses;

use Psr\Http\Message\ResponseInterface;
use Samfelgar\Inter\Charges\Models\Charge;
use Samfelgar\Inter\Common\PsrMessageUtils;
use Webmozart\Assert\Assert;

readonly class GetChargesResponse
{
    /**
     * @param Charge[] $charges
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
        Assert::allIsInstanceOf($this->charges, Charge::class);
    }

    public static function fromResponse(ResponseInterface $response): GetChargesResponse
    {
        $data = PsrMessageUtils::bodyToArray($response);

        return new GetChargesResponse(
            $data['totalPages'],
            $data['totalElements'],
            $data['last'],
            $data['first'],
            $data['size'],
            $data['numberOfElements'],
            \array_map(Charge::fromArray(...), $data['content']),
        );
    }
}
