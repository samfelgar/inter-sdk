<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges\Models;

class Messages
{
    private array $lines = [];

    public function addLine(int $line, string $message): void
    {
        if ($line < 1 || $line > 5) {
            throw new \InvalidArgumentException('The line must be a value be 1 and 5');
        }
        $this->lines[$line - 1] = $message;
    }

    public function getLine(int $line): ?string
    {
        if ($line < 1 || $line > 5) {
            throw new \InvalidArgumentException('The line must be a value be 1 and 5');
        }
        return $this->lines[$line - 1] ?? null;
    }

    public function getLines(): array
    {
        return $this->lines;
    }
}
