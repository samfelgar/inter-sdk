<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Charges;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Samfelgar\Inter\Charges\Models\Charge;
use Samfelgar\Inter\Charges\Requests\CancelChargeRequest;
use Samfelgar\Inter\Charges\Requests\CreateChargeRequest;
use Samfelgar\Inter\Charges\Requests\GetChargesRequest;
use Samfelgar\Inter\Charges\Responses\CreateChargeResponse;
use Samfelgar\Inter\Charges\Responses\GetChargesResponse;
use Samfelgar\Inter\Charges\Responses\GetChargesSummaryResponse;
use Samfelgar\Inter\Common\PsrMessageUtils;
use Samfelgar\Inter\Common\TokenAndCheckingAccountAware;
use Samfelgar\Inter\Webhooks\Webhooks;

class Charges
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
    public function create(CreateChargeRequest $request): CreateChargeResponse
    {
        $response = $this->client->post($this->basePath('boletos'), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
            RequestOptions::JSON => $request,
        ]);
        return CreateChargeResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getCharges(GetChargesRequest $request): GetChargesResponse
    {
        $response = $this->client->get($this->basePath('boletos'), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
            RequestOptions::QUERY => $request->toArray(),
        ]);
        return GetChargesResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getChargesSummary(GetChargesRequest $request): GetChargesSummaryResponse
    {
        $response = $this->client->get($this->basePath('boletos/sumario'), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
            RequestOptions::QUERY => $request->toSummaryArray(),
        ]);
        return GetChargesSummaryResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function getDetailedCharge(string $ourNumber): Charge
    {
        $response = $this->client->get($this->basePath("boletos/{$ourNumber}"), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
        ]);
        return Charge::fromArray(PsrMessageUtils::bodyToArray($response));
    }

    /**
     * Retrieves the charge pdf as base64
     *
     * @throws GuzzleException
     */
    public function getChargePdf(string $ourNumber): string
    {
        $response = $this->client->get($this->basePath("boletos/{$ourNumber}/pdf"), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
        ]);
        $data = PsrMessageUtils::bodyToArray($response);
        return $data['pdf'];
    }

    /**
     * @throws GuzzleException
     */
    public function cancelCharge(CancelChargeRequest $request): void
    {
        $this->client->post($this->basePath("boletos/{$request->ourNumber}/cancelar"), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
            RequestOptions::JSON => $request,
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
}
