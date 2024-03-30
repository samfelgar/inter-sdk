<?php

namespace Samfelgar\Inter\Charges\Requests;

enum CancelReason: string
{
    case Settlement = 'ACERTOS';
    case ClientRequest = 'APEDIDODOCLIENTE';
    case PaidDirectly = 'PAGODIRETOAOCLIENTE';
    case Substitution = 'SUBSTITUICAO';
}
