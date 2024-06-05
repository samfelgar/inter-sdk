<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Banking\Models;

use Brick\PhoneNumber\PhoneNumber;
use Brick\PhoneNumber\PhoneNumberFormat;
use Brick\PhoneNumber\PhoneNumberParseException;
use Webmozart\Assert\Assert;

class Pix
{
    public readonly string $key;

    public function __construct(
        string $key,
        private readonly PixKeyType $type,
    ) {
        $this->key = $this->validateKey($key, $this->type);
    }

    private function validateKey(string $key, PixKeyType $type): string
    {
        return match ($type) {
            PixKeyType::Document => $this->validateDocument($key),
            PixKeyType::Phone => $this->validatePhone($key),
            PixKeyType::Email => $this->validateEmail($key),
            PixKeyType::Evp => $this->validateEvp($key),
        };
    }

    private function validateDocument(string $key): string
    {
        $document = \preg_replace('/\D/', '', $key);
        if (\strlen($document) !== 11 && \strlen($document) !== 14) {
            throw new \InvalidArgumentException('Invalid document');
        }
        return $document;
    }

    private function validatePhone(string $key): string
    {
        try {
            return PhoneNumber::parse($key, 'BR')->format(PhoneNumberFormat::E164);
        } catch (PhoneNumberParseException $e) {
            throw new \InvalidArgumentException('Invalid phone number', previous: $e);
        }
    }

    private function validateEmail(string $key): string
    {
        Assert::email($key);
        return $key;
    }

    private function validateEvp(string $key): string
    {
        Assert::length($key, 36);
        return $key;
    }
}
