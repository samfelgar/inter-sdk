<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Common;

use Psr\Http\Message\ResponseInterface;

class ResponseUtils
{
    public static function responseToArray(ResponseInterface $response): array
    {
        try {
            return \json_decode((string) $response->getBody(), true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \RuntimeException('Invalid response format', previous: $e);
        }
    }
}
