<?php

namespace Samfelgar\Inter\OAuth\Models;

enum Scope: string
{
    case BalanceRead = 'extrato.read';
    case ChargeRead = 'boleto-cobranca.read';
    case ChargeWrite = 'boleto-cobranca.write';
    case PaymentWrite = 'pagamento-boleto.write';
    case PaymentRead = 'pagamento-boleto.read';
    case DarfPaymentWrite = 'pagamento-darf.writepagamento-darf.write';
    case ImmediateChargeWrite = 'cob.write';
    case ImmediateChargeRead = 'cob.read';
    case ImmediateChargeWithDueDateWrite = 'cobv.write';
    case ImmediateChargeWithDueDateRead = 'cobv.read';
    case PixWrite = 'pix.write';
    case PixRead = 'pix.read';
    case WebhookRead = 'webhook.read';
    case WebhookWrite = 'webhook.write';
    case PayloadLocationWrite = 'payloadlocation.write';
    case PayloadLocationRead = 'payloadlocation.read';
    case PixChargeWrite = 'pagamento-pix.write';
    case PixChargeRead = 'pagamento-pix.read';
    case BankingWebhookWrite = 'webhook-banking.write';
    case BankingWebhookRead = 'webhook-banking.read';
}
