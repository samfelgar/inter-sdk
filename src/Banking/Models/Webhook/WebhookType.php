<?php

declare(strict_types=1);

namespace Samfelgar\Inter\Banking\Models\Webhook;

enum WebhookType
{
    case PixPayment;
    case SlipPayment;
}
