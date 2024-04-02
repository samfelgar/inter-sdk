<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Webhooks;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Samfelgar\Inter\Common\TokenAndCheckingAccountAware;
use Samfelgar\Inter\Webhooks\Requests\QueryCallbacksRequest;
use Samfelgar\Inter\Webhooks\Responses\GetWebhookResponse;
use Samfelgar\Inter\Webhooks\Responses\QueryCallbacksResponse;

class Webhooks
{
    use TokenAndCheckingAccountAware;

    private readonly string $basePath;

    public function __construct(
        private readonly Client $client,
        string $token,
        string $basePath,
    ) {
        $this->setToken($token);
        if (\str_ends_with('/', $basePath)) {
            $basePath = \rtrim($basePath, '/');
        }
        $this->basePath = $basePath;
    }

    /**
     * @throws GuzzleException
     */
    public function create(string $url): void
    {
        if (!\str_starts_with('https://', $url)) {
            throw new \InvalidArgumentException('The url must start with https://');
        }

        $this->client->put($this->basePath('webhook'), [
            'headers' => $this->defaultHeaders(),
            'json' => [
                'webhookUrl' => $url,
            ]
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function getWebhook(): GetWebhookResponse
    {
        $response = $this->client->get($this->basePath('webhook'), [
            'headers' => $this->defaultHeaders(),
        ]);
        return GetWebhookResponse::fromResponse($response);
    }

    /**
     * @throws GuzzleException
     */
    public function delete(): void
    {
        $this->client->delete($this->basePath('webhook'), [
            'headers' => $this->defaultHeaders(),
        ]);
    }

    /**
     * @throws GuzzleException
     */
    public function queryCallbacks(QueryCallbacksRequest $request): QueryCallbacksResponse
    {
        $response = $this->client->get($this->basePath('webhook/callbacks'), [
            'headers' => $this->defaultHeaders(),
            'query' => $request->toArray(),
        ]);
        return QueryCallbacksResponse::fromResponse($response);
    }

    private function basePath(string $path): string
    {
        if (!\str_starts_with('/', $path)) {
            $path = '/' . $path;
        }
        return $this->basePath . $path;
    }
}
