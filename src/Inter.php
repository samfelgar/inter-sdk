<?php

declare(strict_types=1);

namespace Samfelgar\Inter;

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\MessageFormatter;
use GuzzleHttp\Middleware;
use Psr\Log\LoggerInterface;
use Samfelgar\Inter\Charges\Charges;
use Samfelgar\Inter\Common\HasToken;
use Samfelgar\Inter\OAuth\Authentication;
use Samfelgar\Inter\Pix\Pix;
use Samfelgar\Inter\PixCharges\PixCharges;

class Inter
{
    use HasToken;

    public const BASE_URI = 'https://cdpj.partners.bancointer.com.br';

    public function __construct(
        private readonly Client $client,
    ) {
    }

    public static function instance(string $keyPath, string $certificatePath, ?LoggerInterface $logger = null): Inter
    {
        $handler = HandlerStack::create();
        if ($logger !== null) {
            $handler->push(Middleware::log($logger, new MessageFormatter(MessageFormatter::DEBUG)));
        }
        $client = new Client([
            'cert' => $certificatePath,
            'ssl_key' => $keyPath,
            'handler' => $handler,
            'base_uri' => self::BASE_URI,
        ]);
        return new Inter($client);
    }

    public function authentication(): Authentication
    {
        return new Authentication($this->client);
    }

    public function charges(): Charges
    {
        $this->assertToken();
        return new Charges($this->client, $this->token);
    }

    public function pixCharges(): PixCharges
    {
        $this->assertToken();
        return new PixCharges($this->client, $this->token);
    }

    public function pix(): Pix
    {
        $this->assertToken();
        return new Pix($this->client, $this->token);
    }
}
