<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Common;

use Psr\Http\Message\MessageInterface;

class PsrMessageUtils
{
    public static function bodyToArray(MessageInterface $message): array
    {
        try {
            return \json_decode((string) $message->getBody(), true, flags: JSON_THROW_ON_ERROR);
        } catch (\JsonException $e) {
            throw new \RuntimeException('Invalid body format', previous: $e);
        }
    }
}
