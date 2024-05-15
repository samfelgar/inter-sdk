<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Webhooks\Responses;

use DateTimeInterface;
use Psr\Http\Message\ResponseInterface;
use Samfelgar\Inter\Common\PsrMessageUtils;

readonly class GetWebhookResponse
{
    public function __construct(
        public string $url,
        public \DateTimeImmutable $createdAt,
    ) {
    }

    public static function fromResponse(ResponseInterface $response): GetWebhookResponse
    {
        $data = PsrMessageUtils::bodyToArray($response);
        return new GetWebhookResponse(
            $data['webhookUrl'],
            \DateTimeImmutable::createFromFormat(DateTimeInterface::RFC3339, $data['criacao'])
        );
    }
}
