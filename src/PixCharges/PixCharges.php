<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Samfelgar\Inter\Common\PsrMessageUtils;
use Samfelgar\Inter\Common\TokenAndCheckingAccountAware;
use Samfelgar\Inter\PixCharges\Requests\CreatePixChargeRequest;
use Samfelgar\Inter\PixCharges\Requests\GetPixChargesRequest;
use Samfelgar\Inter\PixCharges\Responses\GetPixChargeResponse;
use Samfelgar\Inter\PixCharges\Responses\GetPixChargesResponse;
use Samfelgar\Inter\PixCharges\Responses\SummaryItem;
use Samfelgar\Inter\Webhooks\Webhooks;

class PixCharges
{
    use TokenAndCheckingAccountAware;

    public function __construct(
        public readonly Client $client,
        string $token,
    ) {
        $this->setToken($token);
    }

    /**
     * Create a pix charge and returns the request code, which will
     * be used to retrieve the charge later
     *
     * @throws GuzzleException
     */
    public function create(CreatePixChargeRequest $request): string
    {
        $response = $this->client->post($this->basePath('cobrancas'), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
            RequestOptions::JSON => $request,
        ]);
        $data = PsrMessageUtils::bodyToArray($response);
        return $data['codigoSolicitacao'];
    }

    /**
     * @throws GuzzleException
     */
    public function getPixCharges(GetPixChargesRequest $request): GetPixChargesResponse
    {
        $response = $this->client->get($this->basePath('cobrancas'), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
            RequestOptions::QUERY => $request->toArray(),
        ]);
        return GetPixChargesResponse::fromResponse($response);
    }

    /**
     * @return SummaryItem[]
     * @throws GuzzleException
     */
    public function getPixChargesSummary(GetPixChargesRequest $request): array
    {
        $response = $this->client->get($this->basePath('cobrancas/sumario'), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
            RequestOptions::QUERY => $request->toSummaryArray(),
        ]);
        $data = PsrMessageUtils::bodyToArray($response);
        return \array_map(SummaryItem::fromArray(...), $data);
    }

    /**
     * @throws GuzzleException
     */
    public function getPixCharge(string $chargeId): GetPixChargeResponse
    {
        $response = $this->client->get($this->basePath("cobrancas/{$chargeId}"), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
        ]);
        return GetPixChargeResponse::fromResponse($response);
    }

    /**
     * Retrieves the charge pdf as base64
     *
     * @throws GuzzleException
     */
    public function getPixChargePdf(string $chargeId): string
    {
        $response = $this->client->get($this->basePath("cobrancas/{$chargeId}/pdf"), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
        ]);
        $data = PsrMessageUtils::bodyToArray($response);
        return $data['pdf'];
    }

    /**
     * @throws GuzzleException
     */
    public function cancelCharge(string $chargeId, string $reason): void
    {
        $this->client->post($this->basePath("cobrancas/{$chargeId}/cancelar"), [
            RequestOptions::HEADERS => $this->defaultHeaders(),
            RequestOptions::JSON => [
                'motivoCancelamento' => $reason,
            ]
        ]);
    }

    public function webhooks(): Webhooks
    {
        $webhooks = new Webhooks($this->client, $this->token, $this->basePath('cobrancas'));
        if ($this->hasCheckingAccount()) {
            $webhooks->setCheckingAccount($this->checkingAccount);
        }
        return $webhooks;
    }

    private function basePath(string $path): string
    {
        if (!\str_starts_with($path, '/')) {
            $path = '/' . $path;
        }
        return '/cobranca/v3' . $path;
    }
}
