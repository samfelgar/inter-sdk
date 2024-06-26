<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Responses;

use Psr\Http\Message\ResponseInterface;
use Samfelgar\Inter\Common\PsrMessageUtils;

readonly class CreateChargeResponse
{
    public function __construct(
        public string $yourNumber,
        public string $ourNumber,
        public string $barCode,
        public string $line,
    ) {
    }

    public static function fromResponse(ResponseInterface $response): CreateChargeResponse
    {
        $data = PsrMessageUtils::bodyToArray($response);

        return new CreateChargeResponse(
            $data['seuNumero'],
            $data['nossoNumero'],
            $data['codigoBarras'],
            $data['linhaDigitavel'],
        );
    }
}
