<?php

namespace Samfelgar\Inter\Tests\Charges\Models;

use GuzzleHttp\Psr7\Response;
use PHPUnit\Framework\Attributes\Test;
use PHPUnit\Framework\TestCase;
use Samfelgar\Inter\Charges\Models\ChargeWebhookPayload;

class ChargeWebhookPayloadTest extends TestCase
{
    #[Test]
    public function itCanInstantiateFromMap(): void
    {
        $data = [
            "nossoNumero" => "00999999999",
            "seuNumero" => "0000",
            "situacao" => "PAGO",
            "dataHoraSituacao" => "2022-01-10",
            "valorNominal" => 100,
            "valorTotalRecebimento" => 105,
            "codigoBarras" => "00000000000000000000000000000000000000000000",
            "linhaDigitavel" => "00000000000000000000000000000000000000000000000"
        ];

        $payload = ChargeWebhookPayload::fromArray($data);
        $this->assertInstanceOf(ChargeWebhookPayload::class, $payload);
    }

    #[Test]
    public function itCanInstantiateUniqueFromResponse(): void
    {
        $data = [
            "nossoNumero" => "00999999999",
            "seuNumero" => "0000",
            "situacao" => "PAGO",
            "dataHoraSituacao" => "2022-01-10",
            "valorNominal" => 100,
            "valorTotalRecebimento" => 105,
            "codigoBarras" => "00000000000000000000000000000000000000000000",
            "linhaDigitavel" => "00000000000000000000000000000000000000000000000"
        ];

        $response = new Response(body: \json_encode($data));
        $payload = ChargeWebhookPayload::fromResponse($response);
        $this->assertInstanceOf(ChargeWebhookPayload::class, $payload);
    }

    #[Test]
    public function itCanInstantiateMultipleFromResponse(): void
    {
        $data = [
            [
                "nossoNumero" => "00999999999",
                "seuNumero" => "0000",
                "situacao" => "PAGO",
                "dataHoraSituacao" => "2022-01-10",
                "valorNominal" => 100,
                "valorTotalRecebimento" => 105,
                "codigoBarras" => "00000000000000000000000000000000000000000000",
                "linhaDigitavel" => "00000000000000000000000000000000000000000000000"
            ],
            [
                "nossoNumero" => "00999999999",
                "seuNumero" => "0000",
                "situacao" => "PAGO",
                "dataHoraSituacao" => "2022-01-10",
                "valorNominal" => 100,
                "valorTotalRecebimento" => 105,
                "codigoBarras" => "00000000000000000000000000000000000000000000",
                "linhaDigitavel" => "00000000000000000000000000000000000000000000000"
            ]
        ];

        $response = new Response(body: \json_encode($data));
        $payloads = ChargeWebhookPayload::fromResponse($response);
        $this->assertIsArray($payloads);
        foreach ($payloads as $payload) {
            $this->assertInstanceOf(ChargeWebhookPayload::class, $payload);
        }
    }
}
