<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Responses;

use Psr\Http\Message\ResponseInterface;
use Samfelgar\Inter\Common\ResponseUtils;
use Samfelgar\Inter\PixCharges\Models\Charge;
use Samfelgar\Inter\PixCharges\Models\Pix;
use Samfelgar\Inter\PixCharges\Models\Slip;

readonly class GetPixChargeResponse
{
    public function __construct(
        public Charge $charge,
        public Slip $slip,
        public ?Pix $pix,
    ) {
    }

    public static function fromArray(array $data): GetPixChargeResponse
    {
        $charge = Charge::fromArray($data['cobranca']);
        $slip = Slip::fromArray($data['boleto']);
        $pix = isset($data['pix']) ? Pix::fromArray($data['pix']) : null;
        return new GetPixChargeResponse($charge, $slip, $pix);
    }

    public static function fromResponse(ResponseInterface $response): GetPixChargeResponse
    {
        $data = ResponseUtils::responseToArray($response);
        return self::fromArray($data);
    }
}
