<?php

namespace Samfelgar\Inter\Common;

trait TokenAndCheckingAccountAware
{
    use CheckingAccountAware;
    use HasToken;

    protected function defaultHeaders(): array
    {
        $headers = [
            'authorization' => "Bearer {$this->token}"
        ];
        if ($this->hasCheckingAccount()) {
            $headers['x-conta-corrente'] = $this->checkingAccount;
        }
        return $headers;
    }
}
