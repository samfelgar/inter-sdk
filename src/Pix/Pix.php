<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Pix;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Inter\Common\TokenAndCheckingAccountAware;
use Samfelgar\Inter\Pix\Requests\CreateInstantPixRequest;
use Samfelgar\Inter\Pix\Requests\UpdateInstantPixRequest;
use Samfelgar\Inter\Pix\Responses\InstantPixResponse;
use Samfelgar\Inter\Webhooks\Webhooks;
use Webmozart\Assert\Assert;

class Pix
{
    use TokenAndCheckingAccountAware;

    public function __construct(
        private readonly Client $client,
        string $token,
        ?string $checkingAccount = null
    ) {
        $this->setToken($token);
        $this->setCheckingAccount($checkingAccount);
    }

    /**
     * @throws GuzzleException
     */
    public function createInstantChargeWithTxId(CreateInstantPixRequest $request): InstantPixResponse
    {
        Assert::notNull($request->txId);
        $response = $this->client->put("/pix/v2/cob/{$request->txId}", [
            'json' => $request,
            'headers' => $this->defaultHeaders(),
        ]);
        return InstantPixResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function updateInstantCharge(UpdateInstantPixRequest $request): InstantPixResponse
    {
        Assert::notNull($request->txId);
        $response = $this->client->patch("/pix/v2/cob/{$request->txId}", [
            'json' => $request,
            'headers' => $this->defaultHeaders(),
        ]);
        return InstantPixResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getInstantCharge(string $txId): InstantPixResponse
    {
        $response = $this->client->get("/pix/v2/cob/{$txId}", [
            'headers' => $this->defaultHeaders(),
        ]);
        return InstantPixResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function createInstantCharge(CreateInstantPixRequest $request): InstantPixResponse
    {
        $response = $this->client->post('/pix/v2/cob', [
            'json' => $request,
            'headers' => $this->defaultHeaders(),
        ]);
        return InstantPixResponse::fromResponse($response);
    }

    public function webhooks(string $pixKey): Webhooks
    {
        $webhooks = new Webhooks($this->client, $this->token, '/pix/v2', $pixKey);
        if ($this->hasCheckingAccount()) {
            $webhooks->setCheckingAccount($this->checkingAccount);
        }
        return $webhooks;
    }
}
