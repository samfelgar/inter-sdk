<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Models;

enum ChargeReceivementOrigin: string
{
    case Slip = 'BOLETO';
    case Pix = 'PIX';
}
