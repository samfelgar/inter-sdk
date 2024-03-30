<?php

declare(strict_types=1);

namespace Samfelgar\Inter\OAuth\Responses;

use Psr\Http\Message\ResponseInterface;
use Samfelgar\Inter\Common\ResponseUtils;

readonly class TokenResponse
{
    public function __construct(
        public string $accessToken,
        public string $tokenType,
        public int $expiresIn,
        public string $scope,
    ) {
    }

    public static function fromResponse(ResponseInterface $response): TokenResponse
    {
        $data = ResponseUtils::responseToArray($response);
        return new TokenResponse(
            $data['access_token'],
            $data['token_type'],
            $data['expires_in'],
            $data['scope'],
        );
    }
}
