<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Pix\Models;

readonly class Value implements \JsonSerializable
{
    public function __construct(
        public float $amount,
        public bool $canChange,
    ) {
    }

    public static function fromArray(array $data): Value
    {
        return new Value(
            (float) $data['original'],
            $data['modalidadeAlteracao'] === 1
        );
    }

    public function jsonSerialize(): array
    {
        return [
            'original' => \sprintf('%.2f', $this->amount),
            'modalidadeAlteracao' => $this->canChange ? 0 : 1
        ];
    }
}
