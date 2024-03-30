<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Inter\Charges\Models\Charge;
use Samfelgar\Inter\Charges\Requests\CancelChargeRequest;
use Samfelgar\Inter\Charges\Requests\CreateChargeRequest;
use Samfelgar\Inter\Charges\Requests\GetChargesRequest;
use Samfelgar\Inter\Charges\Responses\CreateChargeResponse;
use Samfelgar\Inter\Charges\Responses\GetChargesResponse;
use Samfelgar\Inter\Charges\Responses\GetChargesSummaryResponse;
use Samfelgar\Inter\Common\CheckingAccountAware;
use Samfelgar\Inter\Common\ResponseUtils;
use Samfelgar\Inter\Webhooks\Webhooks;

class Charges
{
    use CheckingAccountAware;

    public function __construct(
        private readonly Client $client,
        private readonly string $token,
        ?string $checkingAccount = null,
    ) {
        $this->setCheckingAccount($checkingAccount);
    }

    /**
     * @throws GuzzleException
     */
    public function create(CreateChargeRequest $request): CreateChargeResponse
    {
        $response = $this->client->post($this->basePath('boletos'), [
            'headers' => $this->defaultHeaders(),
            'json' => $request,
        ]);
        return CreateChargeResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getCharges(GetChargesRequest $request): GetChargesResponse
    {
        $response = $this->client->get($this->basePath('boletos'), [
            'headers' => $this->defaultHeaders(),
            'query' => $request->toArray(),
        ]);
        return GetChargesResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getChargesSummary(GetChargesRequest $request): GetChargesSummaryResponse
    {
        $response = $this->client->get($this->basePath('boletos/sumario'), [
            'headers' => $this->defaultHeaders(),
            'query' => $request->toSummaryArray(),
        ]);
        return GetChargesSummaryResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getDetailedCharge(string $ourNumber): Charge
    {
        $response = $this->client->get($this->basePath("boletos/{$ourNumber}"), [
            'headers' => $this->defaultHeaders(),
        ]);
        return Charge::fromArray(ResponseUtils::responseToArray($response));
    }

    /**
     * Retrieves the charge base64 pdf
     *
     * @throws GuzzleException
     */
    public function getChargePdf(string $ourNumber): string
    {
        $response = $this->client->get($this->basePath("boletos/{$ourNumber}/pdf"), [
            'headers' => $this->defaultHeaders(),
        ]);
        $data = ResponseUtils::responseToArray($response);
        return $data['pdf'];
    }

    /**
     * @throws GuzzleException
     */
    public function cancelCharge(CancelChargeRequest $request): void
    {
        $this->client->post($this->basePath("boletos/{$request->ourNumber}/cancelar"), [
            'headers' => $this->defaultHeaders(),
            'json' => $request,
        ]);
    }

    public function webhooks(): Webhooks
    {
        $webhooks = new Webhooks($this->client, $this->token, $this->basePath('boletos'));
        if ($this->hasCheckingAccount()) {
            $webhooks->setCheckingAccount($this->checkingAccount);
        }
        return $webhooks;
    }

    private function basePath(string $path): string
    {
        if (!\str_starts_with('/', $path)) {
            $path = '/' . $path;
        }
        return '/cobranca/v2' . $path;
    }

    private function defaultHeaders(): array
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
