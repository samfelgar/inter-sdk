<?php

namespace Samfelgar\Inter\Common;

trait CheckingAccountAware
{
    protected ?string $checkingAccount = null;

    public function setCheckingAccount(?string $checkingAccount): static
    {
        $this->checkingAccount = $checkingAccount;
        return $this;
    }

    protected function hasCheckingAccount(): bool
    {
        return $this->checkingAccount !== null;
    }
}
