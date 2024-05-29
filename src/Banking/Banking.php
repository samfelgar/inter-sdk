<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Banking;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Ramsey\Uuid\UuidInterface;
use Samfelgar\Inter\Banking\Requests\CreatePixPaymentRequest;
use Samfelgar\Inter\Banking\Responses\CreatePixPaymentResponse;
use Samfelgar\Inter\Common\TokenAndCheckingAccountAware;

class Banking
{
    use TokenAndCheckingAccountAware;

    public function __construct(
        private readonly Client $client,
        string $token,
        ?string $checkingAccount = null,
    ) {
        $this->setToken($token);
        $this->setCheckingAccount($checkingAccount);
    }

    /**
     * @throws GuzzleException
     */
    public function createPixPayment(UuidInterface $transactionId, CreatePixPaymentRequest $request): CreatePixPaymentResponse
    {
        $response = $this->client->post('/banking/v2/pix', [
            RequestOptions::HEADERS => [
                'x-id-idempotente' => (string) $transactionId,
                ...$this->defaultHeaders(),
            ],
            RequestOptions::JSON => $request,
        ]);
        return CreatePixPaymentResponse::fromResponse($response);
    }
}
