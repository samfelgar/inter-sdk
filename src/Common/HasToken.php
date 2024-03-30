<?php

namespace Samfelgar\Inter\Common;

trait HasToken
{
    protected ?string $token = null;

    public function hasToken(): bool
    {
        return $this->token !== null;
    }

    public function setToken(string $token): static
    {
        $this->token = $token;
        return $this;
    }

    protected function assertToken(): void
    {
        if ($this->token === null) {
            throw new \RuntimeException('The token must be set');
        }
    }
}
