<?php

declare(strict_types=1);

namespace Samfelgar\Inter\PixCharges\Requests;

enum DateReference: string
{
    case DueDate = 'VENCIMENTO';
    case Emission = 'EMISSAO';
    case Payment = 'PAGAMENTO';
}
