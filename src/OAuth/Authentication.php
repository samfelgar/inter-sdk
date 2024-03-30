<?php

declare(strict_types=1);

namespace Samfelgar\Inter\OAuth;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Samfelgar\Inter\OAuth\Models\Scope;
use Samfelgar\Inter\OAuth\Responses\TokenResponse;
use Webmozart\Assert\Assert;

class Authentication
{
    private const GRANT_TYPE = 'client_credentials';

    public function __construct(
        private readonly Client $client,
    ) {
    }

    /**
     * @param Scope[] $scopes
     * @throws GuzzleException
     */
    public function getToken(string $clientId, string $clientSecret, array $scopes = []): TokenResponse
    {
        Assert::allIsInstanceOf($scopes, Scope::class);
        $scopeValues = \array_map(fn (Scope $scope): string => $scope->value, $scopes);
        $response = $this->client->post('/oauth/v2/token', [
            RequestOptions::FORM_PARAMS => [
                'client_id' => $clientId,
                'client_secret' => $clientSecret,
                'grant_type' => self::GRANT_TYPE,
                'scope' => \implode(' ', $scopeValues),
            ],
        ]);
        return TokenResponse::fromResponse($response);
    }
}
