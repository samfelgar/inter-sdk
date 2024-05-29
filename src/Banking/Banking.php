<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Banking;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Ramsey\Uuid\UuidInterface;
use Samfelgar\Inter\Banking\Models\Webhook\WebhookType;
use Samfelgar\Inter\Banking\Requests\CreatePixPaymentRequest;
use Samfelgar\Inter\Banking\Responses\CreatePixPaymentResponse;
use Samfelgar\Inter\Common\TokenAndCheckingAccountAware;
use Samfelgar\Inter\Webhooks\Webhooks;

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

    public function webhooks(WebhookType $type): Webhooks
    {
        $webhookType = match ($type) {
            WebhookType::PixPayment => 'pix-pagamento',
            WebhookType::SlipPayment => 'boleto-pagamento',
        };

        $webhooks = new Webhooks(
            $this->client,
            $this->token,
            '/banking/v2',
            $webhookType,
            'webhooks'
        );
        if ($this->hasCheckingAccount()) {
            $webhooks->setCheckingAccount($this->checkingAccount);
        }
        return $webhooks;
    }
}
